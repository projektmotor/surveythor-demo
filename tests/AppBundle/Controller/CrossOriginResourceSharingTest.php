<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrossOriginResourceSharingTest extends WebTestCase
{
    public static $routes = ['result_next'];

    public function testCORSRoutes()
    {
        $client = static::createClient(
            [],
            [
                'HTTP_ORIGIN' => 'http://surveythor-demo',
            ]
        );

        $client->request(
            'POST',
            'http://surveythor-frontend/result/next/1/8/1'
        );

        if ($client->getResponse()->getStatusCode() !== 200) {
            $this->markTestSkipped('only works locally atm cause we don\'t have another domain in ci yet');
        }

        $this->assertSame(200, $client->getResponse()->getStatusCode(), 'wrong status code');

        $this->assertTrue(
            $client->getResponse()->headers->has('Access-Control-Allow-Origin'),
            'header Access-Control-Allow-Origin not set'
        );
        $this->assertEquals(
            'http://surveythor-demo',
            $client->getResponse()->headers->get('Access-Control-Allow-Origin'),
            'wrong value for header Access-Control-Allow-Origin'
        );
    }
}
