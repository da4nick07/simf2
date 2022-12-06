<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\SpamChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentControllerTest extends WebTestCase
{

    public function testIndexSpam(): void
    {
        // делаем заглушку
        $stub = $this->createStub(SpamChecker::class);
        $stub->method('getSpamScore')
            ->willReturn(2);

        // логинимся
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->find(TEST_USER_ID);
        $client->loginUser($user);

        self::bootKernel();
        $container = static::getContainer();
        // TODO не проходит ($form->isSubmitted() && $form->isValid()) - как симулировать запрос?
        $request = Request::create(
            '/comments/' . TEST_ADMIN_POST_ID . '/new',
            'POST',
            array('id' => TEST_ADMIN_POST_ID,
                'comment_form' =>['body' => 'Тестовый спам',
//                                '_token' =>''
                                ],
            )
        );
        $commentRepository = $container->get(CommentRepository::class);
        $commentController = $container->get(CommentController::class);

        $this->expectException('RuntimeException');
        $commentController->index($request, $commentRepository, $stub);

    }
}
