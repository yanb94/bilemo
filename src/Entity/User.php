<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Annotation\UserAware;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 * @ApiResource(collectionOperations={},itemOperations={"get"},attributes={
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="fullname", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $fullname;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $adresse;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="siret", type="string", length=255, unique=true)
     * @Groups({"read","write"})
     */
    private $siret;

    /**
     * @var string
     * @ORM\Column(name="adresseFacturation", type="string", length=255)
     * @Groups({"read","write"})
     */
    private $adresseFacturation;


    public function __construct($fullname, $facebookId, $email)
    {
        $this->fullname = $fullname;
        $this->username = $facebookId;
        $this->email = $email;
        $this->adresse = "1, Allée Boris Vian";
        $this->phone = "0100000000";
        $this->adresseFacturation = "1, Allée Boris Vian";
        $this->siret = "80519095600013";
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
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
