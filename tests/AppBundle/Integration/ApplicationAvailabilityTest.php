<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ApplicationAvailabilityTest extends WebTestCase
{
    private static $clients = [];
    private static $allUsers = ['anonymous', 'admin', 'editor'];

    public static function setUpBeforeClass()
    {
        foreach (self::$allUsers as $user) {
            $credentials = [];

            if ($user !== 'anonymous') {
                $credentials = [
                    'username' => $user,
                    'password' => $user,
                    'PHP_AUTH_USER' => $user,
                    'PHP_AUTH_PW' => $user,
                ];
            }

            self::$clients[$user] = self::createClient(['environment' => 'test'], $credentials);
        }
    }

    /**
     * @param array  $allowedUsers
     * @param string $url
     *
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($allowedUsers, $url)
    {
        foreach (self::$allUsers as $user) {
            $client = self::$clients[$user];
            $client->request('GET', $url);

            if (in_array($user, $allowedUsers)) {
                $this->assertStatusCode(200, $client);
            } else {
                $this->assertStatusCode($user === 'anonymous' ? 302 : 403, $client);
            }
        }
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        $fixtures = $this->loadFixtureFiles(
            [
                '@AppBundle/DataFixtures/ORM/AllowedOrigin.yml',
                '@AppBundle/DataFixtures/ORM/User.yml',
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

        return [
            [['admin', 'editor'], '/survey/'],
            [['admin', 'editor'], '/survey/new'],
            [['admin', 'editor'], '/survey/edit/'.$fixtures['fragenkatalog']->getId()],
            [['admin', 'editor'], '/survey/update-title/'.$fixtures['fragenkatalog']->getId()],
            [['admin', 'editor'], '/survey/update-result-evaluation-route-name/'.$fixtures['fragenkatalog']->getId()],
            [['admin', 'editor'], '/survey/evaluations/'.$fixtures['fragenkatalog']->getId()],
            [['admin', 'editor'], '/survey-item/form/'.$fixtures['question_single_choice_1']->getId()],
            [['admin', 'editor'], '/survey-item/form/'.$fixtures['item_group_1']->getId()],
            [['admin', 'editor'], '/survey-item/form/'.$fixtures['text_item_survey_text_1']->getId()],
            [['admin', 'editor'], '/survey-item/form/'.$fixtures['text_item_survey_text_1']->getId()],
            [['anonymous', 'admin', 'editor'], '/result/new/'.$fixtures['fragenkatalog']->getId()],
            [['admin'], '/user/list'],
            [['admin'], '/user/create'],
            [['admin'], '/user/edit/'.$fixtures['user_admin']->getId()],
            [['admin'], '/origin/list'],
            [['admin'], '/origin/create'],
            [['admin'], '/origin/edit/'.$fixtures['allowed_origin_1']->getId()],
            // ...
        ];
    }
}
