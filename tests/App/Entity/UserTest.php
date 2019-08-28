<?php

namespace App\Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package App\Tests\App\Entity
 */
class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $user = new User();
        $user->setUsername('Utilisateur 1');
        $user->setPassword('Password 1');
        $user->setEmail('email@email.com');
        $user->setRoles(['ROLE_USER']);
        $this->user = $user;
    }

    public function testUserAttributes()
    {
        $this->assertObjectHasAttribute('id', $this->user);
        $this->assertObjectHasAttribute('username', $this->user);
        $this->assertObjectHasAttribute('password', $this->user);
        $this->assertObjectHasAttribute('email', $this->user);
        $this->assertObjectHasAttribute('roles', $this->user);
        $this->assertObjectHasAttribute('tasks', $this->user);
    }

    public function testUserHaveIdEqualToNull()
    {
        $this->assertNull($this->user->getId());
    }

    public function testUserHaveValidUsername()
    {
        $this->assertContains('Utilisateur 1', $this->user->getUsername());
    }

    public function testUserCanChangeUsername()
    {
        $this->user->setUsername('Utilisateur 2');
        $this->assertContains('Utilisateur 2', $this->user->getUsername());
    }

    public function testUserHaveValidPassword()
    {
        $this->assertContains('Password 1', $this->user->getPassword());
    }

    public function testUserCanChangePassword()
    {
        $this->user->setPassword('Password 2');
        $this->assertContains('Password 2', $this->user->getPassword());
    }

    public function testUserHaveValidEmail()
    {
        $this->assertContains('email@email.com', $this->user->getEmail());
    }

    public function testUserCanChangeEmail()
    {
        $this->user->setEmail('email2@email.com');
        $this->assertContains('email2@email.com', $this->user->getEmail());
    }

    public function testUserMustHaveValidRoles()
    {
        $this->assertContains('ROLE_USER', $this->user->getRoles());
    }

    public function testUserCanChangeRole()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testUserCanRemoveTask()
    {
        $task = new Task();
        $this->user->addTask($task);
        $this->user->removeTask($task);
        $this->assertEquals(0, count($this->user->getTasks()));
    }

}