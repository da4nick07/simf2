<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use  App\Entity\Post;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('111@mail.ru');
        $user1->setPassword(password_hash('111', PASSWORD_DEFAULT));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('222@mail.ru');
        $user2->setPassword(password_hash('222', PASSWORD_DEFAULT));
        $user2->setEnabled();
        $manager->persist($user2);

        $userAdmin = new User();
        $userAdmin->setEmail('555@mail.ru');
        $userAdmin->setPassword(password_hash('555', PASSWORD_DEFAULT));
        $userAdmin->setEnabled();
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $post = new Post();
        $post->setTitle('Самая первая статья');
        $post->setIntro('Описание самой первой статьи');
        $post->setBody('Текст самой первой статьи');
        $post->setUser($user2);
        $manager->persist($post);

        $post = new Post();
        $post->setTitle('Статья 2');
        $post->setIntro('Описание статьи 2');
        $post->setBody('Текст статьи 2');
        $post->setUser($userAdmin);
        $manager->persist($post);

        $manager->flush();
    }
}
