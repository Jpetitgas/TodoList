<?php

namespace App\Tests\Controller\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskEditPageTest extends WebTestCase
{
    use NeedLogin;

    public function testEditNonExistentTask()
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'user']);
        $lasttask = $client->getContainer()->get('doctrine')->getRepository(Task::class)->findBy([], ['id' => 'desc'], 1, 0);
        $id = $lasttask[0]->getId();
        ++$id;
        $this->login($client, $user);
        $client->request('GET', "/tasks/$id/edit");
        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    
}
