<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     */
    public function testPublicUrls(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }

    public function getPublicUrls(): ?\Generator
    {
        yield ['/'];
        yield ['/posts'];
        yield ['/login'];
        yield ['/register'];
    }

    public function testPublicBlogPost(): void
    {
        $client = static::createClient();
        /** @var Post $post */
        $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy([]);
        $client->request('GET', sprintf('/posts/%s', $post->getId()));

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider getSecureToLogin
     */
    public function testSecureToLogin(string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $this->assertResponseRedirects(
            'http://localhost/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function getSecureToLogin(): ?\Generator
    {
        yield ['GET', 'posts/new'];
        yield ['GET', 'posts/2/edit'];
        yield ['GET', 'posts/2/delete'];
        yield ['POST', 'comments/2/new'];
    }

    /**
     * @dataProvider getNotFound
     */
    public function testNotFound(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function getNotFound(): ?\Generator
    {
        yield ['posts/100'];
    }
}