<?php

namespace App\Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskTest
 * @package App\Tests\App\Entity
 */
class TaskTest extends TestCase
{
    /**
     * @var Task
     */
    private $task;

    public function setUp()
    {
        $user = $this->createMock(User::class);
        $task = new Task();
        $task->setTitle('Titre de la tache');
        $task->setContent('Contenu de la tache');
        $task->setUser($user);
        $this->task = $task;
    }

    public function testTaskAttributes()
    {
        $this->assertObjectHasAttribute('id', $this->task);
        $this->assertObjectHasAttribute('title', $this->task);
        $this->assertObjectHasAttribute('content', $this->task);
        $this->assertObjectHasAttribute('isDone', $this->task);
        $this->assertObjectHasAttribute('user', $this->task);
    }

    public function testTaskHaveIdEqualToNull()
    {
        $this->assertNull($this->task->getId());
    }

    public function testTaskHaveValidTitle()
    {
        $this->assertContains('Titre de la tache', $this->task->getTitle());
    }

    public function testTaskCanToChangeTitle()
    {
        $this->task->setTitle('Nouveau titre de la tache');
        $this->assertContains('Nouveau titre de la tache', $this->task->getTitle());
    }

    public function testTaskHaveValidContent()
    {
        $this->assertContains('Contenu de la tache', $this->task->getContent());
    }

    public function testTaskCanChangeContent()
    {
        $this->task->setContent('Nouveau contenu de la tache');
        $this->assertContains('Nouveau contenu de la tache', $this->task->getContent());
    }

    public function testTaskHaveValidCreatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->task->getCreatedAt());
    }

    public function testTaskCanChangeCreatedAt()
    {
        $newDate = new \Datetime('NOW');
        $this->task->setCreatedAt($newDate);
        $this->assertInstanceOf(\DateTime::class, $this->task->getCreatedAt());
    }

    public function testTaskHaveValidIsDone()
    {
        $this->assertEquals(0, $this->task->isDone());
    }

    public function testTaskCanToogleAtask()
    {
        $this->task->toggle(1);
        $this->assertEquals(1, $this->task->isDone());
    }

    public function testTaskHaveValidUser()
    {
        $this->assertInstanceOf(User::class, $this->task->getUser());
    }

    public function testTaskCanChangeUser()
    {
        $newUser = new User();
        $newUser->setUsername('Nouvel utilisateur');
        $this->task->setUser($newUser);
        $this->assertContains('Nouvel utilisateur', $this->task->getUser()->getusername());
    }

    public function testTaskNotHaveValidUser()
    {
        $this->task->setUser(null);
        $this->assertInstanceOf(User::class, $this->task->getUser());
    }
}