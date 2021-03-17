<?php

namespace App\Tests\Controller\User;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateEditDeleteOneTaskTest extends WebTestCase
{
    use NeedLogin;

    public function testCreateNewUser()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $this->login($client, $user);
        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'taskadd';
        $form['task[content]'] = 'tache à faire';
        $client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditLastCreatedTask()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $tasktest = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findOneBy(['title' => 'taskadd']);

        $id = $tasktest->getId();
        $this->login($client, $user);
        $crawler = $client->request('GET', "/tasks/$id/edit");
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'task';
        $form['task[content]'] = 'tache à faire avec encore plus de truc';
        $client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testIfLastModificationDontChangeAuthorTask()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $userId=$user->getId();
        $tasktest = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findOneBy(['title' => 'task']);
        $id = $tasktest->getId();
        $this->assertNotEquals($userId, $id);
        
    }

    public function testToggleTask()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $tasktest = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findOneBy(['title' => 'task']);
        $statut=$tasktest->isDone();
        $id = $tasktest->getId();
        $this->login($client, $user);
        $client->request('GET', "/tasks/$id/toggle");
        $this->assertNotEquals($tasktest->isDone(), $statut);
        $tasktest;
        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteLastCreateTaskNotByOwner()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $tasktest = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findOneBy(['title' => 'task']);
        $id = $tasktest->getId();
        $this->login($client, $user);
        $client->request('GET', "/tasks/$id/delete");
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteLastCreateTaskByOwner()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $tasktest = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findOneBy(['title' => 'task']);
        $id = $tasktest->getId();
        $this->login($client, $user);
        $client->request('GET', "/tasks/$id/delete");
        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
