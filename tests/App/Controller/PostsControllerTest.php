<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        // сначала надо бы выйти на блок  <div class="col bg-info" id = "content">
        $this->assertCount(2, $crawler->filter('p b a'));

        // для проверки собс-но гл.страницы - это лишнее...
        $client->clickLink('Статья 2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Описание статьи 2")');
    }
}
