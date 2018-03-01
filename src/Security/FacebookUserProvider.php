<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use GuzzleHttp\Client;
use JMS\Serializer\Serializer;
use App\Exception\NotGrantedFacebookException;

class FacebookUserProvider implements UserProviderInterface
{
    private $client;

    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getUsernameForApiKey($apiKey)
    {
        $url = 'https://graph.facebook.com/me?access_token='.$apiKey;
        try {
            $response = $this->client->get($url);
        } catch (\Exception $e) {
            throw new NotGrantedFacebookException("The authentification failed");
        }
        
        $res = $response->getBody()->getContents();
        $userData = $this->serializer->deserialize($res, 'array', 'json');

        $username = $userData['name'];

        return $username;
    }

    public function loadUserByUsername($username)
    {
        return new User($username, "", "");
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
