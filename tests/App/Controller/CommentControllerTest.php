<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\UserRepository;
use App\Service\SpamChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{

    public function testAuthorization(string $userMail = '555@mail.ru'): void
    {
        // заходим $user
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByEmail($userMail);
        // simulate $user being logged in
        $client->loginUser($user);
//        $crawler = $client->request('GET', '/');
        $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy([]);
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ' . $userMail . '")');


/*
        $client->request('POST', sprintf('/comments/%d/new', $post->getId()));
//        $client->request('POST', pathpath('comment_new', {'id': post.id})}));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ' . $userMail . '")');
        // Проверка, что отображается форма нового комментария
*/
    }

    public function testIndexSpam(): void
    {
        // делаем заглушку
        $stub = $this->createStub(SpamChecker::class);
        $stub->method('getSpamScore')
            ->willReturn(2);

        // переходим на пост
        $client = static::createClient();
        $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy([]);
        $client->request('GET', sprintf('/posts/%s', $post->getId()));
        $this->assertResponseIsSuccessful();

    }
}
