<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\CommentStateType;

class CommentController extends AbstractController
{
    #[Route('/comments/{id}/new', methods: ['POST'], name: 'comment_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, CommentRepository $commentRepository, SpamChecker $spamChecker): Response
    {
        $postId = $request->get('id');
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
 /*
            $context = [
                'user_ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('user-agent'),
                'referrer' => $request->headers->get('referer'),
                'permalink' => $request->getUri(),
            ];
            if (2 === $spamChecker->getSpamScore($comment, $context)) {
                throw new \RuntimeException('Blatant spam, go away!');
            }
*/
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $commentRepository->addComment([
                'body' => $comment->getBody(),
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user->getId(),
                'post_id' => $postId,
                'state' => CommentStateType::PUBLISHED->value,
            ]);


//            return $this->redirectToRoute('post_show', [
//                'id' => $postId
//            ]);
        }
        return $this->redirectToRoute('post_show', [
            'id' => $postId
        ]);
    }
}
