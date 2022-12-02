<?php

namespace App\Controller\Author;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class PostsControllerTest  extends WebTestCase
{
    /**
     *
     * Редактировать может либо автор, либо админ
     *
     * @dataProvider getUrls
     */
    public function testAccessDenied(string $userMail, string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByEmail($userMail);
        $client->loginUser($user);
        $client->request($httpMethod, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function getUrls(): ?\Generator
    {
        yield ['222@mail.ru', 'GET', '/posts/3/edit'];
    }

    /**
     *
     * Редактировать может либо автор, либо админ
     *
     * @dataProvider getEditUrls
     */
    public function testCanEdit(string $userMail, string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByEmail($userMail);
        $client->loginUser($user);
        $client->request($httpMethod, $url);

        $this->assertResponseIsSuccessful();
    }

    public function getEditUrls(): ?\Generator
    {
        yield ['222@mail.ru', 'GET', '/posts/2/edit'];
        yield ['555@mail.ru', 'GET', '/posts/2/edit'];
    }
}