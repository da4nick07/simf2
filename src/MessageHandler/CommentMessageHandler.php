<?php

namespace App\MessageHandler;

use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Enum\CommentStateType;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/*
// Вариант обработчика с атрибутом
#[AsMessageHandler]
class CommentMessageHandler
*/
// Вариант обработчика с интерфейсом
// + объявить его (обработчик) в services.yaml
class CommentMessageHandler implements MessageHandlerInterface
{
    private $spamChecker;
    private $entityManager;
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, SpamChecker $spamChecker, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->spamChecker = $spamChecker;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(CommentMessage $message)
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) {
            return;
        }

        if (2 === $this->spamChecker->getSpamScore($comment, $message->getContext())) {
            $comment->setState(CommentStateType::SPAM);
        } else {
            $comment->setState(CommentStateType::PUBLISHED);
        }

        $this->entityManager->flush();
    }
}
