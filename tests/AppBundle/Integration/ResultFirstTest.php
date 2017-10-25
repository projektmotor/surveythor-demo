<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItems\Question;

class ResultFirstTest extends WebTestCase
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
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($secondChoice->getText(), $crawler->text());
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
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($secondChoice->getText(), $crawler->text());
    }

    public function testTextChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_text'];
        /** @var Question $firstSurveyItem */
        $firstSurveyItem = $fixtures['question_text_1'];
        /** @var Choice $firstChoice */
        $firstChoice = $fixtures['choice_text_ceo_1'];
        /** @var Choice $secondChoice */
        $secondChoice = $fixtures['choice_text_admin_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getText(), $crawler->text());
        $this->assertContains($firstChoice->getText(), $crawler->text());
        $this->assertContains($secondChoice->getText(), $crawler->text());
    }

    public function testSurveyGroupSingleAndMultipleChoice()
    {
        $fixtures = $this->loadAllFixturesWithoutUsersAndAllowedOrigins();

        /** @var Survey $survey */
        $survey = $fixtures['survey_group_single_and_multiple_choice'];
        /** @var ItemGroup $firstSurveyItem */
        $firstSurveyItem = $fixtures['item_group_1'];
        /** @var Question $firstQuestion */
        $firstQuestion = $fixtures['question_group_single_choice_1_1'];
        /** @var Question $secondQuestion */
        $secondQuestion = $fixtures['question_group_single_choice_2_1'];
        /** @var Question $thirdQuestion */
        $thirdQuestion = $fixtures['question_group_multiple_choice_1_1'];
        /** @var Question $fourthQuestion */
        $fourthQuestion = $fixtures['question_group_multiple_choice_2_1'];

        $url = 'result/first/'.$survey->getId();
        $client = static::makeClient();
        $crawler = $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
        $this->assertContains($survey->getTitle(), $crawler->text());
        $this->assertContains($firstSurveyItem->getTitle(), $crawler->text());
        $this->assertContains($firstQuestion->getText(), $crawler->text());
        $this->assertContains($secondQuestion->getText(), $crawler->text());
        $this->assertContains($thirdQuestion->getText(), $crawler->text());
        $this->assertContains($fourthQuestion->getText(), $crawler->text());
    }

    /**
     * @return array
     */
    private function loadAllFixturesWithoutUsersAndAllowedOrigins()
    {
        return $this->loadFixtureFiles(
            [
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
    }
}
