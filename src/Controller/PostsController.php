<?php

namespace App\Controller;

use App\Form\PostType;
use App\Form\CommentFormType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
use App\Service\TestSrv;
use VLib;

require_once dirname(__FILE__) . '/../VLib/func.php';

class PostsController extends AbstractController
{

    #[Route('/posts', methods: ['GET'], name: 'app_posts')]
    #[Route('/', methods: ['GET'], name: 'homepage')]
    public function index(PostRepository $postRepository, Environment $twig): Response
    {
/*
        // если шаблон для вывода статьи задаётся "внутри" posts/index2.html.twig
        return $this->render('posts/index2.html.twig', [
            'posts' => $postRepository->readAllJoined(),
            'showInsert' => $this->isGranted('ROLE_ADMIN')
        ]);
*/

        // шаблон для вывода статьи задаём как параметр
        return $this->render('posts/index2.html.twig', [
            '_SEARCH' => true,
            'posts' => $postRepository->readAllJoined(),
            'tpl' => $twig->load('posts/_article_tpl.html.twig'),
            'showInsert' => $this->isGranted('ROLE_ADMIN')
        ]);
/*
        // Весь блок формируем "снаружи"
        $posts = $postRepository->readAllJoined();
        $tpl = $twig->load('posts/_article_tpl.html.twig');
        $articls = '';
        foreach ( $posts as $post ) {
            $articls .= $tpl->render( [
                    'id' => $post['id'],
                    'title' => $post['title'],
                    'body' => $post['body'],
                    'nick' => $post['email'],
                    'created_at' => $post['created_at']
            ]);
        }

        return $this->render('posts/index2.html.twig', [
            'articls' => $articls,
            'showInsert' => $this->isGranted('ROLE_ADMIN')
        ]);
*/
//        для отладки
//        return $this->render('base2.html.twig', );
    }

    #[Route('/posts/{id}', methods: ['GET'], name: 'post_show')]
    // ParamConverter штука интересная, но получить безликое и неуправляемое 404 "Страница не найдена"... Не хочу
    public function post(int $id, PostRepository $postRepository, CommentRepository $commentRepository, Environment $twig, TestSrv $testSrv): Response
    {

//        $root = dirname( __FILE__, 1);

        $post = $postRepository->readOneJoined($id);
        if (!$post) {
            // исключение можно и своё бросить...
            throw $this->createNotFoundException(
                'Статья не найдена, id '.$id
            );
        }

        $showDelete = $this->isGranted('ROLE_ADMIN');
        /** @var User $user */
        $user = $this->getUser();
        if ( is_null( $user) ) {
            $showEdit = false;
        } else {
            $showEdit = $showDelete || ( $post['user_id'] === $user->getId() );
        }

        return $this->render('posts/show.html.twig', [
            'post' => $post,
            'comments' => $commentRepository->readAllByPost($id),
            'com_tpl' => $twig->load('posts/_comment_tpl.html.twig'),
//            'comment_form' => $form->createView(),
            'comment_form' => $this->createForm(CommentFormType::class)->createView(),
            'showEdit' => $showEdit,
            'showDelete' => $showDelete,
            'testSrv' => $testSrv->testMsg(),
            'ok' => VLib\myFunc()
       ]);
    }

//    #[Route('/posts/new', methods: ['POST'], name: 'post_new', priority: 1)]
    #[Route('/posts/new', name: 'post_new', priority: 1)]
    // будет переадресация на 'app_login'
    // пока оставил так
    #[IsGranted("ROLE_ADMIN")]
    public function addPost(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
//            $post->setUser($this->getUser());
/*
    // Оставил как пример техн. возможности. Жизнь длинная...
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
*/
            $postId = $postRepository->addPost( [
                'title'=>$post->getTitle(),
                'intro'=>$post->getIntro(),
                'body'=>$post->getBody(),
                'created_at'=>date('Y-m-d H:i:s'),
                'user_id'=>$user->getId()
            ]);

            // возврат к общему списку
//            return $this->redirectToRoute('app_posts');
            // а хочется увидеть сохранённое
            return $this->redirectToRoute('post_show', [
                'id' => $postId
            ]);

        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/{id}/edit', name: 'post_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $post = $doctrine->getRepository( Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'Статья не найдена, id '.$id
            );
        }

        /** @var User $user */
        $user = $this->getUser();
        $canEdit = $this->isGranted("ROLE_ADMIN") || $post->getUser()->getId() === $user->getId();
        if (!$canEdit) {
            throw $this->createAccessDeniedException(
                'У Вас нет прав!'
            );
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/{id}/delete', name: 'post_delete')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'Статья не найдена, id '.$id
            );
        }
        $postRepository->remove( $post, true);

        return $this->redirectToRoute('app_posts');
    }

    #[Route('/posts/search', methods: ['GET'], name: 'post_search', priority: 1)]
    public function search(Request $request, PostRepository $postRepository, Environment $twig): Response
    {
        $value = (string)$request->query->get('t');
        // шаблон для вывода статьи задаём как параметр
        return $this->render('posts/index2.html.twig', [
            '_SEARCH' => true,
            'posts' => $postRepository->findByTitle($value),
            'tpl' => $twig->load('posts/_article_tpl.html.twig'),
            'showInsert' => $this->isGranted('ROLE_ADMIN'),
            'content_title' => 'Результаты поиска'
        ]);
    }

    /**
     *
     * @param int $authorId
     * @return bool
     */
    public function canEdit(int $authorId): bool
    {
        /** @var User $user */
        $curUser = $this->getUser();

        return $this->isGranted('IS_AUTHENTICATED_FULLY') &&
            ( ( $this->isGranted('ROLE_ADMIN') ) or ($authorId === $curUser->getId()));

    }
}
