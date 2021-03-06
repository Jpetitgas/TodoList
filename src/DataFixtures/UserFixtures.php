<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        for ($i = 1; $i < 16; ++$i) {
            $task = new Task();
            $task->setTitle('task'.$i);
            $task->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce varius at ligula nec sollicitudin.');
            $task->setCreatedAt(new DateTime());

            if ($i < 6) {
                $task->setUser(null);
            } elseif ($i > 5 && $i < 11) {
                $task->setUser($user);
            } elseif ($i > 10) {
                $task->setUser($admin);
            }
            $manager->persist($task);
        }

        $manager->flush();
    }
}
