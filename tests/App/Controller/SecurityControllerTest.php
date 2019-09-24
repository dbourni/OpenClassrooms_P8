<?php

namespace App\Tests\App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest
 * @package App\Tests\App\Controller
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLoginAction()
    {
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/login');
        $crawler = $this->client->submitForm('Se connecter', [
            '_username' => 'david',
            '_password' => 'evdbevdb'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLogoutCheck()
    {
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/logout');
        $this->assertResponseIsSuccessful();
        $this->assertContains('Se connecter', $crawler->filter('button')->text());
    }
}