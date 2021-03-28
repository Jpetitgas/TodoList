<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateEditDeleteOneUserTest extends WebTestCase
{
    use NeedLogin;

    public function testCreateNewUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $this->login($client, $user);
        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'usertest';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'email@todo.com';
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditLastCreatedUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $usertest=$client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'usertest']);
        $id=$usertest->getId();
        $this->login($client, $user);
        $crawler = $client->request('GET', "/users/$id/edit");
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'usertestmodified';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'email@todo.com';
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteLastCreateUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $usertest=$client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'usertestmodified']);
        $id=$usertest->getId();
        $this->login($client, $user);
        $client->request('GET', "/users/$id/delete");
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
