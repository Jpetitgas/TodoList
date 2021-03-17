<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TasksListPageTest extends WebTestCase
{
    use NeedLogin;
    public function testTasksPage()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectionToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseRedirects('/login');
    }

    public function testAuthencatedUserAccess()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $this->login($client, $user);
        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }    
    
}
