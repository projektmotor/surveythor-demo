<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use PM\SurveythorBundle\Entity\Survey;

class CrossOriginResourceSharingTest extends WebTestCase
{
    public static $routes = ['result_next'];

    public function testCORSRoutes()
    {
        $fixtures = $this->loadFixtureFiles(
            [
                '@AppBundle/DataFixtures/ORM/AllowedOrigin.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/Choice.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/Condition.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/QuestionTemplate.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/ResultItemTemplate.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/ResultRange.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/Survey.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/SurveyItems.ItemGroup.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/SurveyItems.Question.yml',
                '@PMSurveythorBundle/DataFixtures/ORM/SurveyItems.TextItem.yml',
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

        $surveyRepository = $client->getContainer()->get('app.repository.survey_repository');

        $survey = $surveyRepository->find($survey->getId());
        $result = $survey->getResults()->first();

        $uri = '/result/next/'.$fixtures['it_themen']->getId().'/'.$result->getId();

        $client->request('POST', $uri);

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
