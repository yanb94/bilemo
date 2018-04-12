<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Annotation\UserAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @UniqueEntity("siret")
 * @ApiResource(collectionOperations={"get","post"={"route_name"="api_users_post"}},itemOperations={"get"},attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "denormalization_context"={"groups"={"write"}}
 * })
 * @UserAware(userFieldName="id")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 80)
     * @ORM\Column(name="fullname", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $fullname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 10, max = 80)
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $adresse;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 9, max = 20)
     * @ORM\Column(name="phone", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $phone;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 9, max = 20)
     * @ORM\Column(name="siret", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $siret;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 10, max = 80)
     * @ORM\Column(name="adresseFacturation", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $adresseFacturation;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 8, max = 80)
     * @Groups({"write"})
     */
    private $plainPassword;

    /**
    * @ORM\Column(name="salt", type="string", length=255)
    */
    private $salt;


    public function __construct()
    {
        if (is_null($this->id)) {
            $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        }
    }

    /**
     * @return mixed
     */
    public function getAdresseFacturation()
    {
        return $this->adresseFacturation;
    }

    /**
     * @param mixed $adresseFacturation
     *
     * @return self
     */
    public function setAdresseFacturation($adresseFacturation)
    {
        $this->adresseFacturation = $adresseFacturation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     *
     * @return self
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     *
     * @return self
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     *
     * @return self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * @param mixed $siret
     *
     * @return self
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param mixed $salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
