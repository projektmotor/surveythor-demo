<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @param $url
     *
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $credentials = [
//            'username' => 'admin',
//            'password' => 'admin',
        ];

        $client = static::makeClient($credentials);
        $client->request('GET', $url);
        $this->isSuccessful($client->getResponse());
    }

    public function urlProvider()
    {
        return [
            ['/survey/'],
            ['/survey/edit/1'],
            ['/survey/evaluations/1'],
            ['/result/new/1'],
            ['/users'],
            ['/origin/list'],
            ['/origin/create'],
            ['/origin/edit/ac570913-2545-4b5f-b676-4544444aa274'],
            // ...
        ];
    }
}
