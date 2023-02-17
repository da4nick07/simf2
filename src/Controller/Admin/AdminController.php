<?php

namespace App\Controller\Admin;

use App\Form\CommentFormType;
use App\Form\UsersFilterFormType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

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

    #[Route('/users_get', name: 'users_get')]
    public function usersGet(Request $request, UserRepository $userRepository): Response
    {
        // если метод get - то поля формы достаём так
        $value = (int)($request->query->get('_state') ?? 1);
        switch ($value) {
            case 3:
                $users = $userRepository->readAll();
                break;
            case 2:
                $users = $userRepository->readByEnabled(1);
                break;
            default:
                $users = $userRepository->readByEnabled(0);
         }


        return $this->render('admin/users_get.html.twig', [
            'users' => $users,
            'state' => $value
        ]);
    }

    #[Route('/users_post', methods: ['GET', 'POST'], name: 'users_post')]
    public function usersPost(Request $request, UserRepository $userRepository): Response
    {
//        $formData = null;
//        $formData = ['_state'=>null];
//        $form = $this->createForm(UsersFilterFormType::class, $formData);
        $form = $this->createForm(UsersFilterFormType::class);
        $form->handleRequest($request);

        if ( $request->isMethod('GET')) {
            $method = 'GET';
            $_state = 0;
        } else {
            $method = 'PUT';
//            $_state = $request->request->getInt('_state');
            $_state = $request->request->get('_state');
        }
        // Оставил для отладки
        $isSubmitted = $form->isSubmitted() ? 'ДА' : 'НЕТ';
        $out = '$' . $_state . ';';


        return $this->render('admin/users_post.html.twig', [
            'form' => $form->createView(),
            '_state' => $_state,
            'out' => $out,
            'isSubmitted' => $isSubmitted,
            'method' => $method,
//            'users' => $userRepository->findBy(['enabled'=>$_state]),
        ]);
    }

    #[Route('/users/{id}/enable', methods: ['GET'], name: 'user_enable')]
    public function userEnable(int $id, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            // исключение можно и своё бросить...
            throw $this->createNotFoundException(
                'Пользователь не найден, id '.$id
            );
        }

        $user->setEnabled();
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('users');
    }
}
