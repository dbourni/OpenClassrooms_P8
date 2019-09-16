<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_DAVID = 'David';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$username, $password, $email, $roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);

            if ($username == 'David') {
                $this->addReference(self::USER_DAVID, $user);
            }
        }

        $manager->flush();

    }

    private function getUserData(): array
    {
        return [
            ['David', 'password', 'david@gmail.com', ['ROLE_ADMIN']],
            ['Jean', 'password', 'jean@gmail.com', ['ROLE_USER']],
            ['Pierre', 'password', 'pierre@gmail.com', ['ROLE_USER']],
            ['Marie', 'password', 'marie@gmail.com', ['ROLE_USER']]
        ];
    }

}