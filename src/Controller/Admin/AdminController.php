<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/base_adm.html.twig');
    }

    #[Route('/comments', methods: ['GET'], name: 'comments')]
    public function comments(Request $request, CommentRepository $commentRepository): Response
    {
        return $this->render('admin/comments.html.twig', [
//            'posts' => $postRepository->readAllJoined(),
//            'tpl' => $twig->load('posts/_article_tpl.html.twig'),
//            'showInsert' => $this->isGranted('ROLE_ADMIN')
        ]);
    }

    #[Route('/users', methods: ['GET'], name: 'users')]
    public function users(Request $request, CommentRepository $commentRepository): Response
    {
        return $this->render('admin/users.html.twig', [
//            'posts' => $postRepository->readAllJoined(),
//            'tpl' => $twig->load('posts/_article_tpl.html.twig'),
//            'showInsert' => $this->isGranted('ROLE_ADMIN')
        ]);
    }
}
