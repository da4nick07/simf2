<?php

namespace App\Controller\Admin;

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
        $value = $request->query->get('_state') ?? 1;
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
        $out = '$' . $value;


        return $this->render('admin/users_get.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users_post', name: 'users_post')]
    public function usersPost(Request $request, UserRepository $userRepository): Response
    {
        $value = (string)$request->query->getInt('st');   //get('st');
        $out = '$' . $value;

        $value = (string)$request->query->getInt('st2');
        $out2 = '$' . $value;

        $value = (string)$request->query->get('t');
        $out3 = '$' . $value;

//        if ($value === 3) {$value}

        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->readAll(),
//            'users' => $userRepository->findBy(['enabled'=>$out]),
            'state' => $out,
            'state2' => $out2,
            'state3' => $out3,
//            'showInsert' => $this->isGranted('ROLE_ADMIN')
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
