<?php

namespace Tests\AppBundle\Integration;

use AppBundle\Entity\Result;
use AppBundle\Entity\ResultItem;
use AppBundle\Entity\Survey;
use AppBundle\EventListener\ResponseHeaderListener;
use AppBundle\Repository\ResultRepository;
use AppBundle\Repository\SurveyRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * @covers ResponseHeaderListener
 */
class CrossOriginResourceSharingTest extends WebTestCase
{
    public static $routes = ['result_next', 'result_first', 'result_prev', 'result_last', '*_result_evaluation'];

    /**
     * @var Client
     */
    private $client;

    public function testCORSRoutes()
    {
        $this->client = static::createClient(
            [],
            [
                'HTTP_ORIGIN' => 'http://surveythor-demo',
            ]
        );

        $uris = $this->getUrisWithCorsHeader();

        foreach ($uris as $uri) {
            $this->client->request('GET', $uri);

            $this->assertContains(
                $this->client->getResponse()->getStatusCode(),
                [302, 200],
                'wrong status code for uri '.$uri
            );

            $this->assertTrue(
                $this->client->getResponse()->headers->has('Access-Control-Allow-Origin'),
                'header Access-Control-Allow-Origin not set on uri '.$uri
            );
            $this->assertEquals(
                'http://surveythor-demo',
                $this->client->getResponse()->headers->get('Access-Control-Allow-Origin'),
                'wrong value for header Access-Control-Allow-Origin on uri '.$uri
            );
        }
    }

    private function createResultBySurvey(Survey $survey): Result
    {
        // create first result for survey
        $uri = '/result/first/'.$survey->getId();
        $this->client->request('POST', $uri);

        $this->assertSame(
            200,
            $this->client->getResponse()->getStatusCode(),
            'wrong status code for uri '.$this->client->getRequest()->getUri()
        );

        $surveyRepository = $this->client->getContainer()->get(SurveyRepository::class);

        $survey = $surveyRepository->find($survey->getId());
        /** @var Result $result */
        $result = $survey->getResults()->first();

        return $result;
    }

    private function getSurvey(): Survey
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

        return $survey;
    }

    private function getUrisWithCorsHeader()
    {
        $survey = $this->getSurvey();
        $result = $this->createResultBySurvey($survey);
        $router = $this->client->getContainer()->get('router');
        $resultRepository = $this->client->getContainer()->get(ResultRepository::class);
        $resultId = $result->getId();

        /** @var ResultItem $lastResultItem */
        $lastResultItem = $result->getResultItems()->last();

        $uris = [];
        foreach (self::$routes as $routeName) {
            switch ($routeName) {
                case 'result_first':
                    $uri = $router->generate('result_first', ['survey' => $survey->getId()]);
                    $uris[$routeName] = $uri;
                    break;
                case 'result_next':
                    $resultItemId = $result->getCurrentResultItem()->getId();
                    $uri = $router->generate('result_next', ['result' => $resultId, 'resultItem' => $resultItemId]);
                    $uris[$routeName] = $uri;
                    break;
                case 'result_prev':
                    $lastResultItem->setIsCurrent();
                    $resultItemId = $lastResultItem->getId();
                    $resultRepository->save($result);
                    $uri = $router->generate('result_prev', ['result' => $resultId, 'resultItem' => $resultItemId]);
                    $uris[$routeName] = $uri;
                    break;
                case 'result_last':
                    $lastResultItem->setIsCurrent();
                    $resultItemId = $lastResultItem->getId();
                    $resultRepository->save($result);
                    $uri = $router->generate('result_last', ['result' => $resultId, 'resultItem' => $resultItemId]);
                    $uris[$routeName] = $uri;
                    break;
                case '*_result_evaluation':
                    $uri = $router->generate('bunny_result_evaluation', ['result' => $resultId]);
                    $uris[$routeName] = $uri;
                    $uri = $router->generate('custom_result_evaluation', ['result' => $resultId]);
                    $uris[$routeName] = $uri;
                    break;
                default:
                    $this->fail('no route generation provided for route '.$routeName);
                    break;
            }
        }

        return $uris;
    }
}
