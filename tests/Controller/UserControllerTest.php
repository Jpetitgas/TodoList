<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserPage()
    {
       $client=static::createClient();
       $client->request('GET', '/users');
       $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testRedirectionToLogin()
    {
       $client=static::createClient();
       $client->request('GET', '/users');
       $this->assertResponseRedirects('/login');
    }
}