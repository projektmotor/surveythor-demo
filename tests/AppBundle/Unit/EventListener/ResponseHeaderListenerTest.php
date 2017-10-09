<?php

namespace Tests\AppBundle\Unit\EventListener;

use AppBundle\Entity\AllowedOrigin;
use AppBundle\EventListener\ResponseHeaderListener;
use AppBundle\Repository\AllowedOriginRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ResponseHeaderListenerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AllowedOriginRepository
     */
    private $allowedOriginRepository;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FilterResponseEvent
     */
    private $event;
    /**
     * @var Response
     */
    private $response;

    public function testRequestIsXmlHttpRequest()
    {
        $request = $this->mockRequest(true);
        $listener = $this->createListener($request);

        $this->allowedOriginRepository->expects($this->never())
            ->method('findOneActiveByOriginName');

        $listener->onKernelResponse($this->event);

        $this->assertFalse(
            $this->response->headers->has('Access-Control-Allow-Origin'),
            'header Access-Control-Allow-Origin should not be present'
        );
    }

    public function testAccessControlAllowOriginHeaderSet()
    {
        $requestHeaderOrigin = 'http://allowed.origin';
        $route = 'result_next';
        $request = $this->mockRequest(true, $requestHeaderOrigin, $route);
        $listener = $this->createListener($request);

        $allowedOrigin = new AllowedOrigin();
        $allowedOrigin->setOriginName($requestHeaderOrigin);

        $this->allowedOriginRepository->expects($this->once())
            ->method('findOneActiveByOriginName')
            ->with($requestHeaderOrigin)
            ->willReturn($allowedOrigin);

        $listener->onKernelResponse($this->event);

        $this->assertTrue(
            $this->response->headers->has('Access-Control-Allow-Origin'),
            'header Access-Control-Allow-Origin should be present'
        );

        $this->assertSame(
            $this->response->headers->get('Access-Control-Allow-Origin'),
            $requestHeaderOrigin
        );
    }

    /**
     * @return ResponseHeaderListener
     */
    private function createListener(Request $request)
    {
        $this->allowedOriginRepository = $this->getMockBuilder(AllowedOriginRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array('findOneActiveByOriginName'))
            ->getMockForAbstractClass();
        $this->event = $this->getMockBuilder(FilterResponseEvent::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getRequest', 'getResponse', 'getRequestType'))
            ->getMock();

        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));

        $this->response = new Response();

        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));

        return new ResponseHeaderListener($this->allowedOriginRepository);
    }

    /**
     * @param bool        $isXmlHttpRequest
     * @param string|null $requestHeaderOrigin
     * @param string|null $route
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function mockRequest($isXmlHttpRequest, $requestHeaderOrigin = null, $route = null)
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['isXmlHttpRequest', 'getSchemeAndHttpHost', 'get'])
            ->getMockForAbstractClass();

        $request->headers = new ParameterBag(['origin' => $requestHeaderOrigin]);

        $request->expects($this->any())
            ->method('isXmlHttpRequest')
            ->will($this->returnValue($isXmlHttpRequest));

        $request->expects($this->any())
            ->method('get')
            ->with('_route')
            ->will($this->returnValue($route));

        return $request;
    }
}
