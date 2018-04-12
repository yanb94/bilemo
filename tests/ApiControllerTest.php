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

    public function testAuthentification()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            json_encode([
              "username" => "username",
              "password" => "password"
            ])
        );

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

        $token = $this->fakeLogin($client);

        $crawler = $client->request(
            'GET',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json",
                "HTTP_AUTHORIZATION" => $token

            ]
        );

        $this->assertSame($code, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testAddMember()
    {
        $client = static::createClient();

        $token = $this->fakeLogin($client);

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
                "HTTP_ACCEPT" => "application/json",
                "HTTP_AUTHORIZATION" => $token

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

        $token = $this->fakeLogin($client);

        $data = json_encode($data);

        $crawler = $client->request(
            'POST',
            '/api/members',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json",
                "HTTP_AUTHORIZATION" => $token

            ],
            $data
        );

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteMember()
    {
        $client = static::createClient();

        $token = $this->fakeLogin($client);

        $crawler = $client->request(
            'DELETE',
            '/api/members/2',
            [],
            [],
            [
            "HTTP_ACCEPT" => "application/json",
            "HTTP_AUTHORIZATION" => $token
            ]
        );

        $this->assertSame(204, $client->getResponse()->getStatusCode());
    }

    public function testBadAuthentification()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            json_encode([
              "username" => "badusername",
              "password" => "badpassword"
            ])
        );

        $this->assertSame(401, $client->getResponse()->getStatusCode());
    }

    public function testaddUser()
    {
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
              'CONTENT_TYPE' => 'application/json',
              "HTTP_ACCEPT" => "application/json"

            ],
            json_encode([
              "username"=> "yanb94",
              "fullname"=> "Ma Companie",
              "email"=> "moi@moi.fr",
              "adresse"=> "Mon adresse",
              "phone"=> "014589327",
              "siret"=> "7825000333",
              "adresseFacturation"=> "Mon adresse",
              "plainPassword"=> "password"
            ])
        );

        $this->assertSame(201, $client->getResponse()->getStatusCode());


        $crawler = $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            json_encode([
              "username" => "yanb94",
              "password" => "password"
            ])
        );

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider badAddMemberData
     */
    public function testBadAddUser($data)
    {
        $data = json_encode($data);

        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            $data
        );

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    private function fakeLogin($client)
    {
        $crawler = $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                "HTTP_ACCEPT" => "application/json"

            ],
            json_encode([
              "username" => "username",
              "password" => "password"
            ])
        );

        $token = "Bearer ".json_decode($client->getResponse()->getContent())->token;

        return $token;
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

    public function badAddMemberData()
    {
        return [
          [
            // Already use data
            [
              "username"=> "yanb94",
              "fullname"=> "Ma Companie",
              "email"=> "moi@moi.fr",
              "adresse"=> "Mon adresse",
              "phone"=> "014589327",
              "siret"=> "7825000333",
              "adresseFacturation"=> "Mon adresse",
              "plainPassword"=> "password"
            ]
          ],
          [
            // Bad email
            [
              "username"=> "yanpp",
              "fullname"=> "Ma Companie",
              "email"=> "moimoi.fr",
              "adresse"=> "Mon adresse",
              "phone"=> "014589327",
              "siret"=> "7825000333",
              "adresseFacturation"=> "Mon adresse",
              "plainPassword"=> "password"
            ]
          ],
          [
            // Blank Field
            [
              "username"=> "",
              "fullname"=> "Ma Companie",
              "email"=> "",
              "adresse"=> "Mon adresse",
              "phone"=> "014589327",
              "siret"=> "",
              "adresseFacturation"=> "Mon adresse",
              "plainPassword"=> "password"
            ]
          ],
          [
             // Blank Field
            [
              "username"=> "yanb943333",
              "fullname"=> "",
              "email"=> "moi@moi.fr",
              "adresse"=> "",
              "phone"=> "",
              "siret"=> "7825000333",
              "adresseFacturation"=> "",
              "plainPassword"=> ""
            ]
          ]
        ];
    }
}
