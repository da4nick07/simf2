<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class PostsController extends AbstractController
{

    #[Route('/posts', name: 'app_posts')]
    #[Route('/', name: 'homepage')]
    public function index(PostRepository $postRepository): Response
    {
/*
        return $this->render('posts/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
*/
        return $this->render('posts/index.html.twig', [
            'posts' => $postRepository->readAllJoined()
        ]);
    }

    #[Route('/posts/{id}', name: 'post_show')]
    // ParamConverter штука интересная, но получить безликое и неуправляемое 404 "Страница не найдена"... Не хочу
    public function post(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->readOneJoined($id);
        if (!$post) {
            // исключение можно и своё бросить...
            throw $this->createNotFoundException(
                'Статья не найдена, id '.$id
            );
        }
        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/posts/new', name: 'post_new', priority: 1)]
    // Оставил как пример техн. возможности. Жизнь длинная...
//    public function addPost(Request $request, ManagerRegistry $doctrine): Response
    public function addPost(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new DateTimeImmutable());
/*
    // Оставил как пример техн. возможности. Жизнь длинная...
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
*/
            $postRepository->add( $post, true);

            // возврат к общему списку
//            return $this->redirectToRoute('app_posts');
            // а хочется увидеть сохранённое
            return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);

        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/{id}/edit', name: 'post_edit')]
    public function edit(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        $post = $doctrine->getRepository( Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'Статья не найдена, id '.$id
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

    #[Route('/posts/search', name: 'post_search', priority: 1)]
    public function search(Request $request, PostRepository $postRepository): Response
    {
        $value = (string)$request->query->get('t');
        $posts = $postRepository->findByTitle($value);

        return $this->render('posts/search.html.twig', [
            'posts' => $posts
        ]);
    }
}
