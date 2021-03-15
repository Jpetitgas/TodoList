<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getUser(): User
    {
        $user= new User();
        $user->setUsername('user1');
        $user->setEmail('user1@todo.com');
        $user->setPassword('password');
        return $user;
    }
    public function assertHasError(User $user, int $number=0){
        self::bootKernel();
        $error=self::$container->get('validator')->validate($user);
        $this->assertCount($number,$error);
    }
    
    public function testValidEntity()
    {
        $this->assertHasError($this->getUser(),0);
    }
    
    public function testInvalidUsername()
    {
        $user=$this->getUser();
        $user->setUsername('');
        $this->assertHasError($user,1);
    }
    
    public function testInvalidEmail()
    {
        $user=$this->getUser();
        $user->setEmail('user1todo.com');
        $this->assertHasError($user,1);
        $user->setEmail('');
        $this->assertHasError($user,1);
        $user->setEmail('admin@todo.com');
        $this->assertHasError($user,1);
    }

}
