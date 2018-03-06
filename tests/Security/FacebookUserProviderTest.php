<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\FacebookUserProvider;
use PHPUnit\Framework\TestCase;

class FacebookUserProviderTest extends TestCase
{
    public function testLoadUserByUsernameReturningAUser()
    {
        $client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $serializer = $this
            ->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this
            ->getMockBuilder('App\Repository\UserRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $em
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository)
            ;

        $repository
            ->expects($this->once())
            ->method('__call')
            ->willReturn(null);

        $response = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock();
        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn($response)
            ;

        $streamedResponse = $this
            ->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($streamedResponse);

        $userData = ['name' => 'username', 'id' => "33333", 'email'=> "example@example.com"];
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userData);

        $facebookUserProvider = new FacebookUserProvider($client, $serializer, $em);

        $user = $facebookUserProvider->loadUserByUsername('an-access-token');

        $expectedUser = new User($userData['name'], $userData["id"], $userData['email']);

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
    }
}
