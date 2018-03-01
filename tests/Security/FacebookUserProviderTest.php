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

        $userData = ['name' => 'username'];
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userData);

        $facebookUserProvider = new FacebookUserProvider($client, $serializer);
        $username = $facebookUserProvider->getUsernameForApiKey('an-access-token');

        $user = $facebookUserProvider->loadUserByUsername($username);

        $expectedUser = new User($userData['name'], "", "");

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
    }
}
