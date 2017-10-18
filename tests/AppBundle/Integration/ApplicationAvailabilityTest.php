<?php

namespace Tests\AppBundle\Integration;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @param array  $allowedUsers
     * @param string $url
     *
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($allowedUsers, $url)
    {
        $allUsers = ['anonymous', 'admin', 'editor'];

        foreach ($allUsers as $user) {
            $credentials = [];

            if ($user !== 'anonymous') {
                $credentials = [
                    'username' => $user,
                    'password' => $user,
                ];
            }

            $client = static::makeClient($credentials);
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

        return [
            [['admin', 'editor'], '/survey/'],
            [['admin', 'editor'], '/survey/edit/'.$fixtures['fragenkatalog']->getId()],
            [['admin', 'editor'], '/survey/evaluations/'.$fixtures['fragenkatalog']->getId()],
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
