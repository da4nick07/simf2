<?php

namespace App\MessageHandler;

use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Enum\CommentStateType;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
class CommentMessageHandler
{
    private SpamChecker $spamChecker;
    private EntityManagerInterface $entityManager;
    private CommentRepository $commentRepository;
    private WorkflowInterface $commentPublishingStateMachine;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager, SpamChecker $spamChecker, CommentRepository $commentRepository,
                                WorkflowInterface $commentPublishingStateMachine, MessageBusInterface $bus, MailerInterface $mailer,
                                LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->spamChecker = $spamChecker;
        $this->commentRepository = $commentRepository;
        $this->commentPublishingStateMachine = $commentPublishingStateMachine;
        $this->bus = $bus;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function __invoke(CommentMessage $message)
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) {
            return;
        }

        if ($this->commentPublishingStateMachine->can($comment, 'review')) {
            $this->commentPublishingStateMachine->apply($comment, 'review');
            $this->entityManager->flush();
            $this->bus->dispatch($message);
        } elseif ( $comment->getPublishingPlace() === 'submitted') {
            $score = $this->spamChecker->getSpamScore($comment, $message->getContext());

            $transition = match ($score) {
                0 => 'publish',
                2 => 'to_spam',
                default => 'to_ham'
            };
            $this->commentPublishingStateMachine->apply($comment, $transition);
            $this->entityManager->flush();
            $this->bus->dispatch($message);

            if ( $transition ===  'to_ham') {
                $email = (new NotificationEmail())
                    ->subject('New HAM comment posted')
                    ->htmlTemplate('emails/comment_notification.html.twig')
                    ->from($_ENV['ADMIN_EMAIL'])
                    ->to($_ENV['ADMIN_EMAIL'])
                    ->context(['comment' => $comment,
                                'state' => $comment->getPublishingPlace(),
                                'user' => $comment->getUser()->getEmail(),
                        ]);

                try {
//            $logger->info('Щас отправлю...!');
                    $this->mailer->send($email);
                    // для отладки
//            $logger->error('Письмо отправлено!');
                } catch (TransportExceptionInterface $e) {
                    $this->logger->error('Ошибка отправки почты!');
                }
            }

        }


    }
}
