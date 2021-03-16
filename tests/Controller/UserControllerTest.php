<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testUsersPage()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectionToLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertResponseRedirects('/login');
    }

    public function testAuthencatedUserAccessUsersPageWithAdminRole()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $this->login($client, $user);
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAuthencatedUserAccessUsersPageWithoutAdminRole()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $this->login($client, $user);
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

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
        //$form['user[roles][]']='Utilisateur';
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditNonExistentUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $lastuser=$client->getContainer()->get('doctrine')->getRepository(User::class)->findBy(array(), array('id' => 'desc'),1,0);
        $id=$lastuser[0]->getId();
        $id++;
        $this->login($client, $user);
        $client->request('GET', "/users/$id/edit");
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
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
        //$form['user[roles][]']='Utilisateur';
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
    
    public function testDeleteNonExistentUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $lastuser=$client->getContainer()->get('doctrine')->getRepository(User::class)->findBy(array(), array('id' => 'desc'),1,0);
        $id=$lastuser[0]->getId();
        $id++;
        $this->login($client, $user);
        $client->request('GET', "/users/$id/delete");
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
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
