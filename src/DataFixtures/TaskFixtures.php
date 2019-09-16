<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTaskData() as [$title, $content, $isDone]) {
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($content);
            $task->setCreatedAt(new \DateTime());
            $task->setUser($this->getReference(UserFixtures::USER_DAVID));
            $manager->persist($task);
        }

        $manager->flush();
    }

    private function getTaskData(): array
    {
        return [
            ['Tache 1', 'Contenu de la tache 1', 1],
            ['Tache 2', 'Contenu de la tache 2', 0],
            ['Tache 3', 'Contenu de la tache 2', 0],
        ];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}