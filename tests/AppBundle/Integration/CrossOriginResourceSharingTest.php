<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\Entity\Result;
use AppBundle\Entity\Survey;
use AppBundle\Repository\SurveyRepository;

class CrossOriginResourceSharingTest extends WebTestCase
{
    public static $routes = ['result_next'];

    public function testCORSRoutes()
    {
        $fixtures = $this->loadFixtureFiles(
            [
                '@AppBundle/DataFixtures/ORM/AllowedOrigin.yml',
                '@AppBundle/DataFixtures/ORM/Choice.yml',
                '@AppBundle/DataFixtures/ORM/Condition.yml',
                '@AppBundle/DataFixtures/ORM/QuestionTemplate.yml',
                '@AppBundle/DataFixtures/ORM/ResultItemTemplate.yml',
                '@AppBundle/DataFixtures/ORM/ResultRange.yml',
                '@AppBundle/DataFixtures/ORM/Survey.yml',
                '@AppBundle/DataFixtures/ORM/SurveyItems.ItemGroup.yml',
                '@AppBundle/DataFixtures/ORM/SurveyItems.Question.yml',
                '@AppBundle/DataFixtures/ORM/SurveyItems.TextItem.yml',
            ]
        );

        /** @var Survey $survey */
        $survey = $fixtures['fragenkatalog'];

        $client = static::createClient(
            [],
            [
                'HTTP_ORIGIN' => 'http://surveythor-demo',
            ]
        );

        // create first result for survey
        $uri = '/result/first/'.$survey->getId();
        $client->request('POST', $uri);

        $this->assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            'wrong status code for uri '.$client->getRequest()->getUri()
        );

        $surveyRepository = $client->getContainer()->get(SurveyRepository::class);

        $survey = $surveyRepository->find($survey->getId());
        /** @var Result $result */
        $result = $survey->getResults()->first();

        $uri = '/result/next/'.$result->getCurrentResultItem()->getId().'/'.$result->getId();

        $client->request('GET', $uri);

        $this->assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            'wrong status code for uri '.$client->getRequest()->getUri()
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
