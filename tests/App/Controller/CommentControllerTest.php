<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Service\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/*
 * ВНИМАНИЕ
 * В асинхр. режиме не работает...
 */

class CommentControllerTest extends WebTestCase
{
    public function testMock(): void
    {
        $c = new Comment();

        $mock = $this->getMockBuilder(SpamChecker::class)
            ->disableOriginalConstructor()
//            ->onlyMethods(['getSpamScore'])
            ->getMock();
        $mock->method('getSpamScore')
            ->willReturn(2);

        $this->assertInstanceOf(SpamChecker::class, $mock);
        $this->assertEquals(2, $mock->getSpamScore( $c, []));

        $client = static::createClient();
        $container = $client->getContainer();

        $this->assertTrue($container->has(SpamChecker::class));

        $container->set(SpamChecker::class, $mock);
        // ВАЖНО  - запрещаем перезагрузку ядра
        $client->disableReboot();

        /** @var  $spamChecker SpamChecker */
        $spamChecker = $container->get(SpamChecker::class);
        $this->assertEquals(2, $spamChecker->getSpamScore( $c, []));

        $user = $container->get(UserRepository::class)->find(TEST_USER_ID);
        $client->loginUser($user);
        $client->request('GET', '/posts/' . TEST_ADMIN_POST_ID);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ")');

        $spamChecker = $container->get(SpamChecker::class);
        $this->assertEquals(2, $spamChecker->getSpamScore( $c, []));

    }

    public function testIndexSpam(): void
    {
        /*
         * По доке https://dev.to/nikolastojilj12/symfony-5-mocking-private-autowired-services-in-controller-functional-tests-24j4
         * Подмена д.б. ПОСЛЕ createClient() и ДО request
         *  + запрет на перезагрузку ядра, если запросов больше одного.
         */

        $mock = $this->getMockBuilder(SpamChecker::class)
            ->disableOriginalConstructor()
//            ->onlyMethods(['getSpamScore'])
            ->getMock();
        $mock->method('getSpamScore')
            ->willReturn(2);

        $client = static::createClient();
        $container = $client->getContainer();
/*
        $c = new Comment();
        $this->assertInstanceOf(SpamChecker::class, $mock);
        $this->assertEquals(2, $mock->getSpamScore( $c, []));


        $container->set(SpamChecker::class, $mock);
        // ВАЖНО  - запрещаем перезагрузку ядра
        $client->disableReboot();
        $spamChecker = $container->get(SpamChecker::class);
        $this->assertEquals(2, $spamChecker->getSpamScore( $c, []));


        $user = $container->get(UserRepository::class)->find(TEST_USER_ID);
        $client->loginUser($user);
        $client->request('GET', '/posts/' . TEST_ADMIN_POST_ID);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ")');


        $spamChecker = $container->get(SpamChecker::class);
        $this->assertEquals(2, $spamChecker->getSpamScore( $c, []));
*/

        $user = $container->get(UserRepository::class)->find(TEST_USER_ID);
        $client->loginUser($user);
        $client->request('GET', '/posts/' . TEST_ADMIN_POST_ID);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ")');

//        $this->assertInstanceOf(SpamChecker::class, $mock);
        $container->set(SpamChecker::class, $mock);
        // ВАЖНО  - запрещаем перезагрузку ядра
        $client->disableReboot();
        $spamChecker = $container->get(SpamChecker::class);
        $c = new Comment();
        $this->assertEquals(2, $spamChecker->getSpamScore( $c, []));


        $crawler = $client->submitForm('Отправить', [
            'comment_form[body]' => 'spam',
        ]);
//        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorNotExists('div:contains("spam")');

    }
    public function testIndexNotSpam2(): void
    {
        /*
         * По доке https://dev.to/nikolastojilj12/symfony-5-mocking-private-autowired-services-in-controller-functional-tests-24j4
         * Подмена д.б. ПОСЛЕ createClient() и ДО request
         *  + запрет на перезагрузку ядра, если запросов больше одного.
         */

        $mock = $this->getMockBuilder(SpamChecker::class)
            ->disableOriginalConstructor()
//            ->onlyMethods(['getSpamScore'])
            ->getMock();
        $mock->method('getSpamScore')
            ->willReturn(0);
//            ->will($this->returnArgument(0));

        $client = static::createClient();
        $container = $client->getContainer();

        $user = $container->get(UserRepository::class)->find(TEST_USER_ID);
        $client->loginUser($user);
        $client->request('GET', '/posts/' . TEST_ADMIN_POST_ID);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Привет, ")');

//        $this->assertInstanceOf(SpamChecker::class, $mock);
        $container->set(SpamChecker::class, $mock);
        // ВАЖНО  - запрещаем перезагрузку ядра
        $client->disableReboot();
        $spamChecker = $container->get(SpamChecker::class);
        $c = new Comment();
        $this->assertEquals(0, $spamChecker->getSpamScore( $c, []));


        $crawler = $client->submitForm('Отправить', [
            'comment_form[body]' => 'Тестовый коммент',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Тестовый коммент")');

    }
}
