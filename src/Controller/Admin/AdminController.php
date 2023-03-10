<?php

namespace App\Controller\Admin;

use App\Enum\CommentStateType;
use App\Form\CommentsFilterFormType;
use App\Form\UsersFilterFormType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserState;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/base_adm.html.twig');
    }

    #[Route('/comments', methods: ['GET', 'POST'], name: 'comments')]
    public function comments(Request $request, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentsFilterFormType::class);
        $form->handleRequest($request);

        $_state = CommentStateType::SUBMITTED;
        $_startDate = new \DateTime('-2 days');
        $_endDate = new \DateTime('now'); //  23:59:59.999
        if ( $request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CommentStateType $_state */
                $_state = $form->getData()['_state'];
                $_startDate = $form->getData()['_startDate'];
                $_endDate = $form->getData()['_endDate'];
            }
        }
        $_startDate = $_startDate->format('Y-m-d H:i:s');
        $_endDate = $_endDate->format('Y-m-d' . ' 23:59:59.999');

        $comments = $commentRepository->readAllByState($_state->value, $_startDate, $_endDate);

        return $this->render('admin/comments.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments,
            'status' =>$_state->value,
            'startDate' =>$_startDate,
            'endDate' =>$_endDate,
        ]);
    }

    #[Route('/comments_ajax', name: 'comments_ajax')]
    public function commentsAjax(Request $request, CommentRepository $commentRepository): Response
    {

        // проверка на AJAX запрос
        if ($request->isXmlHttpRequest()) {
            // ес-но, что $users можно передать как параметр или в сессию положить...
            $comments = $commentRepository->readAllByState($_POST['status'], $_POST['startDate'], $_POST['endDate']);


            if ( isset( $_POST['sortby'] )) {
                switch ($_POST['sortby'] ) {
                    case 1:
                        if ( $_POST['desc'] == -1) {
                            function cmp2(array $a, array $b) {
                                return $b['id'] <=> $a['id'];
                            }
                        } else {
                            function cmp2(array $a, array $b) {
                                return $a['id'] <=> $b['id'];
                            }
                        }
                        break;
                    case 3:
                        if ( $_POST['desc'] == -1) {
                            function cmp2(array $a, array $b) {
                                return $b['created_at'] <=> $a['created_at'];
                            }
                        } else {
                            function cmp2(array $a, array $b) {
                                return $a['created_at'] <=> $b['created_at'];
                            }
                        }
                        break;
                    case 4:
                        if ( $_POST['desc'] == -1) {
                            function cmp2(array $a, array $b) {
                                return $b['email'] <=> $a['email'];
                            }
                        } else {
                            function cmp2(array $a, array $b) {
                                return $a['email'] <=> $b['email'];
                            }
                        }
                        break;
                }
                usort($comments, 'App\Controller\Admin\cmp2');
            }

            return $this->render('admin/_comments_table.html.twig', [
                'comments' => $comments,
                'status' =>$_POST['status'],
                'sortby' =>$_POST['sortby'],
                'desc' => $_POST['desc'],
            ]);
        }

        return $this->render('admin/_comments_table.html.twig', [
            'comments' => [],
            'status' =>0,
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
        // дефолтные значения в форме - унёс в форму
//        $formData = ['_state'=>UserState::NotEnabled];
        $form = $this->createForm(UsersFilterFormType::class);
        $form->handleRequest($request);

        $_state = UserState::NotEnabled;
        if ( $request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UserState $_state */
                $_state = $form->getData()['_state'];
            }
        }

        switch ($_state) {
            case UserState::All:
                $users = $userRepository->readAll();
                $status = -1;
                break;
            case UserState::Enabled:
                $users = $userRepository->readByEnabled(1);
                $status = 1;
                break;
            default:
                $users = $userRepository->readByEnabled(0);
                $status = 0;
        }

        return $this->render('admin/users_post.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'status' =>$status,
        ]);
    }

    #[Route('/users_ajax', name: 'users_ajax')]
    public function usersAjax(Request $request, UserRepository $userRepository): Response
    {
        // проверка на AJAX запрос
        if ($request->isXmlHttpRequest()) {
            // ес-но, что $users можно передать как параметр или в сессию положить...
            $users = match ($_POST['status']) {
                '-1' => $userRepository->readAll(),
                '1'  => $userRepository->readByEnabled(1),
                default => $userRepository->readByEnabled(0),
            };


            if ( isset( $_POST['sortby'] )) {
                switch ($_POST['sortby'] ) {
                    case 1:
                        if ( $_POST['desc'] == -1) {
                            function cmp2(array $a, array $b) {
                                return $b['id'] <=> $a['id'];
                            }
                        } else {
                            function cmp2(array $a, array $b) {
                                return $a['id'] <=> $b['id'];
                            }
                        }
                        break;
                    case 2:
                        if ( $_POST['desc'] == -1) {
                            function cmp2(array $a, array $b) {
                                return $b['created_at'] <=> $a['created_at'];
                            }
                        } else {
                            function cmp2(array $a, array $b) {
                                return $a['created_at'] <=> $b['created_at'];
                            }
                        }
                        break;
                }
                usort($users, 'App\Controller\Admin\cmp2');
            }

            return $this->render('admin/_users_table.html.twig', [
                'users' => $users,
                'status' =>$_POST['status'],
                'sortby' =>$_POST['sortby'],
                'desc' => $_POST['desc'],
            ]);
        }
        return $this->render('admin/_users_table.html.twig', [
            'users' => [],
            'status' =>0,
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
