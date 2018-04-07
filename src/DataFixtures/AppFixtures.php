<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Member;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $kernel;

    public function __construct(UserPasswordEncoderInterface $encoder, KernelInterface $kernel)
    {
        $this->encoder = $encoder;
        $this->kernel = $kernel;
    }

    public function load(ObjectManager $manager)
    {

        $data = [
            [
                "name" => "Iphone X",
                "description" => "Ceci est un iphone 7",
                "prix" => 1159.00
            ],
            [
                "name" => "SamSung galaxy A8",
                "description" => "Ceci est un SamSung galaxy A8",
                "prix" => 499.00
            ],
            [
                "name" => "SAMSUNG GALAXY S7",
                "description" => "Ceci est un SAMSUNG GALAXY S7",
                "prix" => 549.00
            ],
            [
                "name" => "SAMSUNG GALAXY J3",
                "description" => "Ceci est un SAMSUNG GALAXY J3",
                "prix" => 199.00
            ],
            [
                "name" => "SAMSUNG GALAXY S8",
                "description" => "Ceci est un SAMSUNG GALAXY S8",
                "prix" => 809.00
            ],
            [
                "name" => "HONOR 5X",
                "description" => "Ceci est un HONOR 5X",
                "prix" => 149.00
            ],
            [
                "name" => "SAMSUNG GALAXY S9",
                "description" => "Ceci est un SAMSUNG GALAXY S9",
                "prix" => 859.00
            ],
            [
                "name" => "BLACKBERRY TORCH 9860",
                "description" => "Ceci est un BLACKBERRY TORCH 9860",
                "prix" => 199.00
            ],
            [
                "name" => "BLACKBERRY 9300 CURVE",
                "description" => "Ceci est un BLACKBERRY 9300 CURVE",
                "prix" => 79.00
            ],
            [
                "name" => "BLACKBERRY BLACKBERRY DTEK50",
                "description" => "Ceci est un BLACKBERRY BLACKBERRY DTEK50",
                "prix" => 184.00
            ],
        ];


        foreach ($data as $value) {
            $product = new Product();
            $product->setName($value['name'])
            ->setDescription($value['description'])
            ->setPrix($value['prix'])
            ;

            $manager->persist($product);
        }

        if ($this->kernel->getEnvironment() == 'test') {
            $user = new User();

            $user->setUsername("username")
            ->setPlainPassword("password")
            ->setFullname("Company")
            ->setEmail("example@example.com")
            ->setAdresse('Mon adresse')
            ->setPhone('0148523188')
            ->setSiret('800055896240')
            ->setAdresseFacturation("Mon adresse")
            ;

            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);

            $memberOne = new Member();
            $memberTwo = new Member();

            $memberOne
                ->setUsername("username")
                ->setFirstname("prénom")
                ->setLastname("nom")
                ->setEmail("coco3@coco.fr")
                ->setBirthdate(new \DateTime('now'))
                ->setAdresse("Une belle adresse")
                ->setPhone("0000000000000")
                ->setClient($user)
            ;

            $memberTwo
                ->setUsername("username2")
                ->setFirstname("prénom")
                ->setLastname("nom")
                ->setEmail("coco2@coco.fr")
                ->setBirthdate(new \DateTime('now'))
                ->setAdresse("Une belle adresse")
                ->setPhone("0000000000000")
                ->setClient($user)
            ;

            $manager->persist($user);
            $manager->persist($memberOne);
            $manager->persist($memberTwo);
        }

        $manager->flush();
    }
}
