<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Message\CommentMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\CommentStateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    #[Route('/comments/{id}/new', methods: ['POST'], name: 'comment_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
//    public function index(Request $request, CommentRepository $commentRepository, SpamChecker $spamChecker): Response
    public function index(Request $request, CommentRepository $commentRepository, MessageBusInterface $bus): Response
    {
        $postId = $request->get('id');
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $commentId = $commentRepository->addComment([
                'body' => $comment->getBody(),
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user->getId(),
                'post_id' => $postId,
                'state' => CommentStateType::SUBMITTED->value,
            ]);

            $context = [
                'user_ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('user-agent'),
                'referrer' => $request->headers->get('referer'),
                'permalink' => $request->getUri(),
            ];
            $bus->dispatch(new CommentMessage($commentId, $context));
/*
            $this->addFlash(
                'notice',
                'Спасибо за Ваш комментарий! После проверки он будет опубликован.'
            );
*/
//            return $this->redirectToRoute('post_show', [
//                'id' => $postId
//            ]);
        }
        return $this->redirectToRoute('post_show', [
            'id' => $postId
        ]);
    }
}
