<?php

namespace App\Tests\Controller\Task;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskCreatePageTest extends WebTestCase
{
    use NeedLogin;
    public function testCreateTaskPageWithoutUserConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectionToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $this->assertResponseRedirects('/login');
    }
    public function testDisplayCreateTaskWithUserConnected()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $this->login($client, $user);
        $client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
