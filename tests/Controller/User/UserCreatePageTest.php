<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserCreatePageTest extends WebTestCase
{
    use NeedLogin;
    public function testDisplayCreateUserWithAdminRole()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $this->login($client, $user);
        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDisplayCreateUserWithoutAdminRole()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $this->login($client, $user);
        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
