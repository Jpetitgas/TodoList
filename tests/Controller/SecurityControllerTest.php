<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler= $client->request('GET', '/login');
        $form=$crawler->selectButton('Se connecter')->form([
            'username'=> 'user',
            'password'=> 'bad'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }
    public function testSuccessfullLogin()
    {
        $client = static::createClient();
        $crawler= $client->request('GET', '/login');
        $form=$crawler->selectButton('Se connecter')->form([
            'username'=> 'admin',
            'password'=> 'admin'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/');
    }
}
