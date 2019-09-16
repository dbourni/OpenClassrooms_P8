<?php

namespace App\Tests\App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package App\Tests\App\Controller
 */
class UserControllerTest extends WebTestCase
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
        $crawler = $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());
    }

    public function testListActionWithAuthorizedUser()
    {
        $this->loginWithUserClient();

        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
    }

    public function testCreateAction()
    {
        $this->loginWithUserClient();

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $createdUserNumber = rand();
        $form['user[username]'] = 'Admin Test ' . $createdUserNumber;
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $form['user[email]'] = 'admin' . $createdUserNumber . '@admin.com';
        $crawler = $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("utilisateur a bien été ajouté.")')->count());
    }

    public function testEditAction()
    {
        $this->loginWithUserClient();

        $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findBy([], ['id' => 'DESC'], 1, 0)[0];
        $crawler = $this->client->request('GET', 'users/'. $user->getId() .'/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = 'password2';
        $form['user[password][second]'] = 'password2';
        $crawler = $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("utilisateur a bien été modifié")')->count());
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