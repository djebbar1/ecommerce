<?php
namespace App\Service;

use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class PasswordHasherService
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(Users $user, $plainPassword)
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}