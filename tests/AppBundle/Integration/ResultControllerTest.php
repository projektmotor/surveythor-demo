<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\Entity\Choice;
use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyItems\ItemGroup;
use AppBundle\Entity\SurveyItems\Question;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\HttpKernel\Client;

class ResultControllerTest extends WebTestCase
{
    public function testSingleChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_single_choice'];
        /** @var Question $firstSurveyItem */
        $firstSurveyItem = $fixtures['question_single_choice_1'];
        /** @var Choice $firstChoice */
        $firstChoice = $fixtures['choice_single_15_1'];
        /** @var Choice $secondChoice */
        $secondChoice = $fixtures['choice_single_50_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($secondChoice->getText(), $crawler->text());

        $client = $this->clickProceedUntilEndOfSurveyReached($crawler, $client);

        $crawler = $this->assertEvaluationResponse($client);

        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($survey->getTitle(), $crawler->text());
    }

    public function testMultipleChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_multiple_choice'];
        /** @var Question $firstSurveyItem */
        $firstSurveyItem = $fixtures['question_multiple_choice_1'];
        /** @var Choice $firstChoice */
        $firstChoice = $fixtures['choice_multiple_sausage_1'];
        /** @var Choice $secondChoice */
        $secondChoice = $fixtures['choice_multiple_milk_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($secondChoice->getText(), $crawler->text());

        $client = $this->clickProceedUntilEndOfSurveyReached($crawler, $client);

        $crawler = $this->assertEvaluationResponse($client);

        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($survey->getTitle(), $crawler->text());
    }

    public function testTextChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_text'];
        /** @var Question $firstSurveyItem */
        $firstSurveyItem = $fixtures['question_text_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());

        $client = $this->clickProceedUntilEndOfSurveyReached($crawler, $client);;

        $crawler = $this->assertEvaluationResponse($client);

        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($survey->getTitle(), $crawler->text());
    }

    public function testSurveyGroupSingleAndMultipleChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_group_single_and_multiple_choice'];
        /** @var ItemGroup $surveyGroupItem */
        $surveyGroupItem = $fixtures['item_group_1'];
        /** @var Question $firstQuestion */
        $firstQuestion = $fixtures['choice_group_single_choice_grapefruit_1'];
        /** @var Question $secondQuestion */
        $secondQuestion = $fixtures['choice_group_single_choice_cakes_1'];
        /** @var Question $thirdQuestion */
        $thirdQuestion = $fixtures['choice_group_multiple_choice_grapefruit_1'];
        /** @var Question $fourthQuestion */
        $fourthQuestion = $fixtures['choice_group_multiple_choice_cakes_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());

        /** @var Question $surveyItem */
        foreach ($surveyGroupItem->getSurveyItems() as $surveyItem) {
            $this->assertContains($surveyItem->getText(), $crawler->text());
        }
        $this->assertContains($firstQuestion->getText(), $crawler->text());
        $this->assertContains($secondQuestion->getText(), $crawler->text());
        $this->assertContains($thirdQuestion->getText(), $crawler->text());
        $this->assertContains($fourthQuestion->getText(), $crawler->text());

        $client = $this->clickProceedUntilEndOfSurveyReached($crawler, $client);

        $crawler = $this->assertEvaluationResponse($client);

        $this->assertContains($secondQuestion->getText(), $crawler->text());
        $this->assertContains($survey->getTitle(), $crawler->text());
    }

    /**
     * @return array
     */
    private function loadAllFixturesWithoutUsersAndAllowedOrigins()
    {
        return $this->loadFixtureFiles(
            [
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
    }

    private function clickProceedUntilEndOfSurveyReached(Crawler $crawler, Client $client): Client
    {
        $formUri = '';

        while ($button = $crawler->filter('[data-test="next"]') and $button->count()) {
            $form = $crawler->filter('form')->form();

            if ($formUri === $form->getUri()) {
                $this->fail(sprintf('form was not successfully submitted with last uri %s', $formUri));
            }
            $formUri = $form->getUri();
            $formValues = [];

            /**
             * @var string    $name
             * @var FormField $formField
             */
            foreach ($form->all() as $name => $formField) {
                if ($formField instanceof ChoiceFormField) {
                    if ($formField->getType() === 'checkbox') {
                        $formField->tick();
                    } else {
                        $formValues[$name] = $formField->availableOptionValues()[0]; // simply use first value
                    }
                } elseif ($formField instanceof TextareaFormField) {
                    $formField->setValue('some text for textarea');
                }
            }

            $form->setValues($formValues);
            $nextUri = $button->attr('data-url');
            $crawler = $client->request('POST', $nextUri, $form->getPhpValues(), $form->getPhpFiles());
        }

        return $client;
    }

    private function assertEvaluationResponse(CLient $client): Crawler
    {
        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $jsonResponseContent = json_decode($client->getResponse()->getContent());
        $this->assertObjectHasAttribute('url', $jsonResponseContent);
        $evaluationUri = $jsonResponseContent->url;

        $crawler = $client->request('GET', $evaluationUri);

        return $crawler;
    }
}
