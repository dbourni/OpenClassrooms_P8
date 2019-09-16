<?php

namespace App\Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;

/**
 * Class TaskControllerTest
 * @package App\Tests\App\Controller
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testListActionWithoutAuthorizedUser()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
    }

    public function testListActionWithAuthorizedUser()
    {
        $this->loginWithUserClient();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Créer une tâche")')->count());
    }

    public function testCreateAction()
    {
        $this->loginWithUserClient();

        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Tache de test';
        $form['task[content]'] = 'Tache de test';
        $crawler = $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count());
    }

    public function testDeleteTaskAction()
    {
        $this->loginWithUserClient();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $task = $entityManager->getRepository(Task::class)->findAll()[0];
        $crawler = $this->client->request('GET', 'tasks/'. $task->getId() .'/delete');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Superbe ! La tâche a bien été supprimée.")')->count());
    }

    public function testEditAction()
    {
        $this->loginWithUserClient();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $task = $entityManager->getRepository(Task::class)->findAll()[0];
        $crawler = $this->client->request('GET', 'tasks/'. $task->getId() .'/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Tache modifiée';
        $form['task[content]'] = 'Tache modifiée';
        $crawler = $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Superbe ! La tâche a bien été modifiée.")')->count());
    }

    public function testToggleTaskAction()
    {
        $this->loginWithUserClient();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $task = $entityManager->getRepository(Task::class)->findAll()[0];
        $crawler = $this->client->request('GET', 'tasks/'. $task->getId() .'/toggle');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Superbe ! La tâche ' . $task->getTitle() . ' a bien été marquée comme faite.")')->count());
    }

    protected function loginWithUserClient()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');
        $crawler = $this->client->submitForm('Se connecter', [
            '_username' => 'david',
            '_password' => 'evdbevdb'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}