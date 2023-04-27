<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private const ROLE = 'ROLE_USER';

    public function get(Request $request): array
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $phone = $request->get('phone');
        $email = $request->get('email');

        return [
            'username' => $username,
            'password' => $password,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phone' => $phone,
            'email' => $email
        ];
    }

    public function set(UserPasswordHasherInterface $passwordHasher, $credentials): User
    {
        $user = new User();
        $user->setUsername($credentials['username']);
        $this->seters($passwordHasher, $credentials, $user);
        $user->setRoles(self::ROLE);

        return $user;
    }

    public function update(UserPasswordHasherInterface $passwordHasher, $credentials, $user): User
    {
        $this->seters($passwordHasher, $credentials, $user);

        return $user;
    }

    public function seters(UserPasswordHasherInterface $passwordHasher, $credentials, $user): void
    {
        $user->setPassword($passwordHasher->hashPassword($user, $credentials['password']));
        $user->setFirstName($credentials['firstName']);
        $user->setLastName($credentials['lastName']);
        $user->setPhone($credentials['phone']);
        $user->setEmail($credentials['email']);
    }
}