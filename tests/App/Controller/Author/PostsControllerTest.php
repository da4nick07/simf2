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
    public function testAccessDenied(int $userId, string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->find($userId);
        $client->loginUser($user);
        $client->request($httpMethod, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function getUrls(): ?\Generator
    {
        yield [TEST_USER_ID, 'GET', '/posts/' . TEST_ADMIN_POST_ID . '/edit'];
    }

    /**
     *
     * Редактировать может либо автор, либо админ
     *
     * @dataProvider getEditUrls
     */
    public function testCanEdit(int $userId, string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->find($userId);
        $client->loginUser($user);
        $client->request($httpMethod, $url);

        $this->assertResponseIsSuccessful();
    }

    public function getEditUrls(): ?\Generator
    {
        yield [TEST_USER_ID, 'GET', '/posts/' . TEST_USER_POST_ID . '/edit'];
        yield [TEST_ADMIN_ID, 'GET', '/posts/' . TEST_USER_POST_ID . '/edit'];
    }
}