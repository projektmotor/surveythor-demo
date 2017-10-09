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
