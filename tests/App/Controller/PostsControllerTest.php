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
        $this->assertCount(2, $crawler->filter('#content > p b a'));

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

    public function testAddPost(): void
    {
        // заходим админом
        $client = static::createClient();
        $admin = $client->getContainer()->get(UserRepository::class)->find(TEST_ADMIN_ID);
        $client->loginUser($admin);
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, 555@mail.ru")');

        $client->clickLink('Добавить');
        $this->assertResponseIsSuccessful();
        $crawler = $client->submitForm('Сохранить', [
            'post[title]' => 'Ещё одна статья',
            'post[intro]' => 'Ещё одна статья',
            'post[body]' => 'Ещё одна статья',
        ]);

//        $crawler = $client->request('GET', '/');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Ещё одна статья")');
    }

}
