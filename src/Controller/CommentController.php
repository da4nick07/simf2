<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comments/{id}/new', methods: ['POST'], name: 'comment_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, CommentRepository $commentRepository): Response
    {
        $postId = $request->get('id');
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $commentRepository->addComment([
                'body' => $comment->getBody(),
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user->getId(),
                'post_id' => $postId,
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
