<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserDeletePageTest extends WebTestCase
{
    use NeedLogin;
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
    
    
}