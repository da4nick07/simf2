<?php

namespace App\MessageHandler;

use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Enum\CommentStateType;

#[AsMessageHandler]
class CommentMessageHandler
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
