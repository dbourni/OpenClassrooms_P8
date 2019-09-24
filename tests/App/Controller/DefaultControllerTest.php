<?php

namespace App\Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package App\Tests\App\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexWithoutAuthorizedUser()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexWithAuthorizedUser()
    {
        $this->loginWithUserClient();

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('To Do List app', $crawler->filter('a')->text());
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
