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
use Doctrine\ORM\EntityManager;

class FacebookUserProvider implements UserProviderInterface
{
    private $client;
    private $em;

    public function __construct(Client $client, Serializer $serializer, EntityManager $em)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function loadUserByUsername($apiKey)
    {
        $url = 'https://graph.facebook.com/me?access_token='.$apiKey.'&fields=id,name,email';
        try {
            $response = $this->client->get($url);
        } catch (\Exception $e) {
            throw new NotGrantedFacebookException("The authentification failed");
        }
        
        $res = $response->getBody()->getContents();
        $userData = $this->serializer->deserialize($res, 'array', 'json');

        $user = $this->em->getRepository(User::class)->findOneByUsername($userData['id']);

        if (!is_null($user)) {
            $user->setFullname($userData['name'])
            ->setEmail($userData['email']);
        } else {
            $user = new User($userData['name'], $userData['id'], $userData['email']);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
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
