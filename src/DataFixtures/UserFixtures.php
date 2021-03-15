<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    protected $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $hash = $this->encoder->encodePassword($admin, 'admin');
        $admin->setUsername('admin');
        $admin->setEmail('admin@todo.com');
        $admin->setPassword($hash);
        $admin->setRoles(['ROLE_ADMIN']);    
        $manager->persist($admin);
        
        $user = new User();
        $hash = $this->encoder->encodePassword($user, 'user');
        $user->setUsername('user');
        $user->setEmail('user@todo.com');
        $user->setPassword($hash);
        $user->setRoles(['ROLE_USER']);     
        $manager->persist($user);
        
        
        $manager->flush();
    }
}
