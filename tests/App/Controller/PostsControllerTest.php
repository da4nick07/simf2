<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        // сначала выходим на блок  <div class="col bg-info" id = "content">
        $crawler = $crawler->filter('#content');
        //  для случайного порядка тестов надо убрать
        $this->assertCount(2, $crawler->filter('p b a'));

        $crawler = $client->request('GET', '/posts');
        $this->assertResponseIsSuccessful();
        // сначала выходим на блок  <div class="col bg-info" id = "content">
        $crawler = $crawler->filter('#content');
        //  для случайного порядка тестов надо убрать
        $this->assertCount(2, $crawler->filter('p b a'));

        // для проверки собс-но гл.страницы - это лишнее...
        $client->clickLink('Статья 2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Описание статьи 2")');
    }

    public function testSearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/posts/search', ['t' => 'Сам']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Самая первая статья")');
    }

    public function testPost(): void
    {
        $client = static::createClient();
        // блин, symfony-demo... А если я не знаю id?...
//        $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->find(3);
        $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy([]);
        $client->request('GET', sprintf('/posts/%s', $post->getId()));

        $this->assertResponseIsSuccessful();
    }

    public function testAddPost(): void
    {
/*
        // заходим админом
        // так не работает... не проходит авторизация
        $client = static::createClient([], [
            'PHP_AUTH_USER' => '555@mail.ru',
            'PHP_AUTH_PW' => '555',
        ]);
*/
        // заходим админом
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneByEmail('555@mail.ru');
        // simulate $admin being logged in
        $client->loginUser($admin);
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, 555@mail.ru")');

        $client->clickLink('Добавить');
        $crawler = $client->submitForm('Сохранить', [
            'post[title]' => 'Ещё одна статья',
            'post[intro]' => 'Ещё одна статья',
            'post[body]' => 'Ещё одна статья',
        ]);

        $crawler = $client->request('GET', '/');
        $this->assertSelectorExists('div:contains("Ещё одна статья")');
    }

}
