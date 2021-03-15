<?php

namespace App\Tests\Entity;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getTask(): Task
    {
        $task= new Task();
        $task->setTitle('tache 1');
        $task->setContent('une tache tres,tres longue à réaliser');
        return $task;
    }
    public function assertHasError(Task $user, int $number=0){
        self::bootKernel();
        $error=self::$container->get('validator')->validate($user);
        $this->assertCount($number,$error);
    }
    
    public function testValidEntity()
    {
        $this->assertHasError($this->getTask(),0);
    }
    
    public function testInvalidTitle()
    {
        $user=$this->getTask();
        $user->setTitle('');
        $this->assertHasError($user,1);
    }
    
    public function testInvalidContent()
    {
        $user=$this->getTask();
        $user->setContent('');
        $this->assertHasError($user,1);
        
    }

}