<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Exception\NotGrantedFacebookException;
use Doctrine\Common\Persistence\ObjectManager;

class ApiControllerTest extends WebTestCase
{
    public function testDoc()
    {
        echo shell_exec('php bin/console doctrine:schema:drop --env=test --force');
        echo shell_exec('php bin/console doctrine:schema:create --env=test');
        echo shell_exec('php bin/console doctrine:fixtures:load --env=test');

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/doc');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlPublicOfGet
     */
    public function testGetProtected($url, $code)
    {
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"
            ]
        );

        $this->assertSame(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlPublicOfGet
     */
    public function testRouteFunctionnal($url, $code)
    {
        $client = static::createClient();

        $client = $this->fakeLogin($client);

        $crawler = $client->request(
            'GET',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ]
        );

        $this->assertSame($code, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testAddMember()
    {
        $client = static::createClient();

        $client = $this->fakeLogin($client);

        $data = [
          "username"=> "username65",
          "firstname"=> "Chien",
          "lastname"=> "Bellier",
          "email"=> "string@string.com",
          "birthdate"=> "2018-03-03T14:13:13.913Z",
          "adresse"=> "1,Allée Bobo",
          "phone"=> "0000000000000"
        ];

        $data = json_encode($data);

        $crawler = $client->request(
            'POST',
            '/api/members',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            $data
        );



        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    /**
     * @dataProvider badMemberData
     */
    public function testBadAddMember($data)
    {
        $client = static::createClient();

        $client = $this->fakeLogin($client);

        $data = json_encode($data);

        $crawler = $client->request(
            'POST',
            '/api/members',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            $data
        );

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteMember()
    {
        $client = static::createClient();

        $client = $this->fakeLogin($client);

        $crawler = $client->request('DELETE', '/api/members/2', [], [], ["HTTP_ACCEPT" => "application/json"]);

        $this->assertSame(204, $client->getResponse()->getStatusCode());
    }

    private function fakeLogin($client)
    {
        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager->getRepository(User::class)->findOneByUsername("0000000000000");

        $facebookProvider = $this->getMockBuilder('App\Security\FacebookUserProvider')
        ->disableOriginalConstructor()
        ->setMethods(['loadUserByUsername'])
        ->getMock();

        $facebookProvider
            ->expects($this->once())
            ->method('loadUserByUsername')
            ->willReturn($user)
        ;

        $client->getContainer()->set('facebook_user_provider', $facebookProvider);

        return $client;
    }

    public function urlPublicOfGet()
    {
        return [
            ['/api/members', 200],
            ['/api/products', 200],
            ['/api/products/1', 200],
            ['/api/members', 200],
            ['/api/members/1', 200],
            ['/api/users/1', 200]
        ];
    }

    public function badMemberData()
    {
        return [
            // existing username
            [
                [
                  "username"=> "username",
                  "firstname"=> "Chien",
                  "lastname"=> "Bellier",
                  "email"=> "string3@string3.com",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "1,Allée Bobo",
                  "phone"=> "0000000000000"
                ]
            ],
            // existing email
            [
                [
                  "username"=> "username999999",
                  "firstname"=> "Chien",
                  "lastname"=> "Bellier",
                  "email"=> "coco3@coco.fr",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "1,Allée Bobo",
                  "phone"=> "0000000000000"
                ]
            ],
            // blank firstname
            [
                [
                  "username"=> "username999999",
                  "firstname"=> "",
                  "lastname"=> "Bellier",
                  "email"=> "coco38888@coco.fr",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "1,Allée Bobo",
                  "phone"=> "0000000000000"
                ]
            ],
            // blank lastname
            [
                [
                  "username"=> "username999999",
                  "firstname"=> "GGGG",
                  "lastname"=> "",
                  "email"=> "coco38888@coco.fr",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "1,Allée Bobo",
                  "phone"=> "0000000000000"
                ]
            ],
            // blank adresse
            [
                [
                  "username"=> "username999999",
                  "firstname"=> "GGGG",
                  "lastname"=> "BBBBB",
                  "email"=> "coco38888@coco.fr",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "",
                  "phone"=> "0000000000000"
                ]
            ],
            // blank phone
            [
                [
                  "username"=> "username999999",
                  "firstname"=> "GGGG",
                  "lastname"=> "BBBBB",
                  "email"=> "coco38888@coco.fr",
                  "birthdate"=> "2018-03-03T14:13:13.913Z",
                  "adresse"=> "36,Allée Opo",
                  "phone"=> ""
                ]
            ]
        ];
    }
}
