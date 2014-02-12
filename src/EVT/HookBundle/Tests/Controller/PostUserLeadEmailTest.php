<?php

namespace EVT\HookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostUserLeadEmailTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/json'];
    }

    public function testReceiveHook()
    {
        $params = [
            'lead' => [
                'event' => [
                    'date' => '2015-12-31T00:00:00+0000',
                    'type' => ['type' => 'type'],
                    'location' => [
                        "lat"=> 10,
                        "long" => 10,
                        "admin_level1" => "Getafe",
                        "admin_level2" => "Madrid",
                        "country" => "Spain"
                    ]
                ],
                'personal_info' => [
                    'name' => 'testUserName',
                    'surname' => 'testUserSurname',
                    'email' => 'valid@email.com',
                    'phone' => '+34 0123456789'
                ]
            ],
            'showroom' => [
                "slug"=> "name",
                "score"=> 0,
                "provider"=> [
                    "id"=> "1",
                    "name"=> "name",
                    "slug"=> "name",
                    "notification_emails"=> [],
                    "managers"=> [],
                    "location"=> [
                        "lat"=> 10,
                        "long"=> 10,
                        "admin_level1"=> "test",
                        "admin_level2"=> "test",
                        "country"=> "Spain"
                    ]
                ],
                "vertical"=> [
                    "domain"=> "test.com",
                    "showrooms"=> []
                ],
                "information_bag"=> [
                    "parameters"=> []
                ],
                "id"=> 1
            ],
            "information_bag"=> [
                "parameters"=> []
            ],
            "created_at"=> "2014-02-12T11=>19=>29+0000",
            "email"=> [
                "email"=> "valid@email.com"
            ],
            "id"=> "1"
        ];
        $mailerMock = $this->getMockBuilder('EVT\EAEBundle\Communication\Email\Emailer')
            ->disableOriginalConstructor()->getMock();
        $mailerMock->expects($this->once())->method('send');

        $this->client->getContainer()->set('evt.mailer', $mailerMock);

        $this->client->request(
            'POST',
            '/hooks/lead/user?apikey=1234',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }
}
 