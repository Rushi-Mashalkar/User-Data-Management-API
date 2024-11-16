<?php

// namespace App\Entity;

// use App\Repository\UserRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: UserRepository::class)]
// class User
// {
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    // public function getId(): ?int
    // {
        // return $this->id;
    // }
// } -->

// src/Entity/User.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    private $email;

    #[ORM\Column(type: 'string', length: 50)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'string', length: 20)]
    private $role;

    // Add getters and setters...
}
