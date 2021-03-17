<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserEditPageTest extends WebTestCase
{
    use NeedLogin;
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

    
}