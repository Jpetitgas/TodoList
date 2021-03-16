<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUsersPage()
    {
       $client=static::createClient();
       $client->request('GET', '/users');
       $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectionToLogin()
    {
       $client=static::createClient();
       $client->request('GET', '/users');
       $this->assertResponseRedirects('http://localhost/login');
    }

    public function testAuthencatedUserAccessUsersPage()
    {
       $client=static::createClient();
       $user = self::$kernel->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username'=>'user']);
       $session=$client->getContainer()->get('session');

       $client->request('GET', '/users');
       $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}