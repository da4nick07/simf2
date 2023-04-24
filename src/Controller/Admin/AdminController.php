<?php

namespace App\Controller\Admin;

use App\MClass\CommentFilter;
use App\Enum\CommentStateType;
use App\Form\CommentsFilterFormType;
use App\Form\UsersFilterFormType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserState;
use Symfony\Component\Workflow\WorkflowInterface;

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
//        $data = array( 'state' => null,  'startDate' => null,  'endDate' => null);
        $cf = new CommentFilter();
        $form = $this->createForm(CommentsFilterFormType::class, $cf);
        $form->handleRequest($request);

        $state = CommentStateType::SUBMITTED;
        $startDate = new \DateTime('-2 days');
        $endDate = new \DateTime('now'); //  23:59:59.999
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CommentStateType $_state */
                $state = $cf->state;
                $startDate = $cf->getStartDate();
                $endDate = $cf->endDate;
            }
        }
        $startDate = $startDate->format('Y-m-d H:i:s');
        $endDate = $endDate->format('Y-m-d' . ' 23:59:59.999');

        $comments = $commentRepository->readAllByState($state->value, $startDate, $endDate);

        return $this->render('admin/comments.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments,
            'status' => $state->value,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function funcCommentsSort(array $comments, int $sortby, int $desc): array
    {
        switch ($sortby) {
            case 1:
                if ($desc == -1) {
                    function cmpComments(array $a, array $b)
                    {
                        return $b['id'] <=> $a['id'];
                    }
                } else {
                    function cmpComments(array $a, array $b)
                    {
                        return $a['id'] <=> $b['id'];
                    }
                }
                break;
            case 3:
                if ($desc == -1) {
                    function cmpComments(array $a, array $b)
                    {
                        return $b['created_at'] <=> $a['created_at'];
                    }
                } else {
                    function cmpComments(array $a, array $b)
                    {
                        return $a['created_at'] <=> $b['created_at'];
                    }
                }
                break;
            case 4:
                if ($desc == -1) {
                    function cmpComments(array $a, array $b)
                    {
                        return $b['email'] <=> $a['email'];
                    }
                } else {
                    function cmpComments(array $a, array $b)
                    {
                        return $a['email'] <=> $b['email'];
                    }
                }
                break;
        }
        usort($comments, 'App\Controller\Admin\cmpComments');

        return $comments;
    }


    #[Route('/comments_sort', methods: 'POST', name: 'comments_sort')]
    public function commentsSort(Request $request, CommentRepository $commentRepository, LoggerInterface $logger): Response
    {
        // проверка на AJAX запрос
        if ($request->isXmlHttpRequest()) {
            // ес-но, что $comments можно передать как параметр или в сессию положить...
            $comments = $commentRepository->readAllByState($_POST['status'], $_POST['startDate'], $_POST['endDate']);


            if ( isset( $_POST['sortby'] )) {
                $comments = $this->funcCommentsSort( $comments, (int)$_POST['sortby'], (int)$_POST['desc']);
            }

            return $this->render('admin/_comments_table.html.twig', [
                'comments' => $comments,
                'status' =>$_POST['status'],
                'sortby' =>$_POST['sortby'],
                'desc' => $_POST['desc'],
                'startDate' =>$_POST['startDate'],
                'endDate' =>$_POST['endDate'],
            ]);
        }

        return $this->render('admin/_comments_table.html.twig', [
            'comments' => [],
            'status' =>0,
        ]);
    }

    #[Route('/comments/{id}/{action}', methods: ['POST'], name: 'comments_action')]
    public function commentAction(int $id, string $action, CommentRepository $commentRepository, WorkflowInterface $commentPublishingStateMachine,
                                  ManagerRegistry $doctrine): Response
    {
        $comment = $commentRepository->find($id);
        if (!$comment) {
            // исключение можно и своё бросить...
            throw $this->createNotFoundException(
                'комментарий не найден, id '.$id
            );
        }

        switch ($action) {
            case 'reject':
                if ($commentPublishingStateMachine->can($comment, 'reject')) {
                    $commentPublishingStateMachine->apply($comment, 'reject');
                    $em = $doctrine->getManager();
                    $em->flush();
                }
                break;
            case 'publish':
                if ($commentPublishingStateMachine->can($comment, 'publish')) {
                    $commentPublishingStateMachine->apply($comment, 'publish');
                    $em = $doctrine->getManager();
                    $em->flush();
                }
                break;
        }

        $comments = $commentRepository->readAllByState( $_POST['status'], $_POST['startDate'], $_POST['endDate'] );
        if ( isset( $_POST['sortby'] )) {
            $comments = $this->funcCommentsSort( $comments, (int)$_POST['sortby'], (int)$_POST['desc']);
            return $this->render('admin/_comments_table.html.twig', [
                'comments' => $comments,
                'status' =>$_POST['status'],
                'startDate' =>$_POST['startDate'],
                'endDate' =>$_POST['endDate'],
                'sortby' =>$_POST['sortby'],
                'desc' => $_POST['desc'],
            ]);
        }
        return $this->render('admin/_comments_table.html.twig', [
            'comments' => $comments,
            'status' =>$_POST['status'],
            'startDate' =>$_POST['startDate'],
            'endDate' =>$_POST['endDate'],
        ]);
    }

    #[Route('/users_get', name: 'users_get')]
    public function usersGet(Request $request, UserRepository $userRepository): Response
    {
        // если метод get - то поля формы достаём так
        $value = (int)($request->query->get('state') ?? 0);
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
        // дефолтные значения в форме
        $form = $this->createForm(UsersFilterFormType::class);
        $form->handleRequest($request);

        $state = UserState::NotEnabled;
        if ( $request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UserState $state */
                $state = $form->getData()['state'];
            }
        }

        switch ($state) {
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
            'status' => $status,
        ]);
    }

    private function funcUsersSort(UserRepository $userRepository, int $status, int $sortby=null, int $desc=null): array
    {
        $users = match ($status) {
            -1 => $userRepository->readAll(),
            1  => $userRepository->readByEnabled(1),
            default => $userRepository->readByEnabled(0),
        };

        if ( ($sortby !== null) and ($desc !== null) ) {
            switch ($sortby ) {
                case 1:
                    if ( $desc == -1) {
                        function cmpUsers(array $a, array $b) {
                            return $b['id'] <=> $a['id'];
                        }
                    } else {
                        function cmpUsers(array $a, array $b) {
                            return $a['id'] <=> $b['id'];
                        }
                    }
                    break;
                case 2:
                    if ( $desc == -1) {
                        function cmpUsers(array $a, array $b) {
                            return $b['created_at'] <=> $a['created_at'];
                        }
                    } else {
                        function cmpUsers(array $a, array $b) {
                            return $a['created_at'] <=> $b['created_at'];
                        }
                    }
                    break;
            }
            usort($users, 'App\Controller\Admin\cmpUsers');
        }

        return $users;
    }

    #[Route('/users_sort', methods: 'POST', name: 'users_sort')]
    public function usersSort(Request $request, UserRepository $userRepository): Response
    {
        // проверка на AJAX запрос
        if ($request->isXmlHttpRequest()) {
            $status = (int)$_POST['status'];
            $sortby =  $_POST['sortby'];
            $desc = $_POST['desc'];
            $users = $this->funcUsersSort($userRepository, $status, $sortby, $desc);
            if (($sortby !== null) and ($desc !== null)) {
                return $this->render('admin/_users_table.html.twig', [
                    'users' => $users,
                    'status' => $status,
                    'sortby' => $sortby,
                    'desc' => $desc,
                ]);
            }

            return $this->render('admin/_users_table.html.twig', [
                'users' => $users,
                'status' => $status,
            ]);
        }
        return $this->render('admin/_users_table.html.twig', [
            'users' => [],
            'status' => 0,
        ]);
    }

    #[Route('/users/{id}/{action}', methods: 'POST', name: 'user_action')]
    public function userAction(Request $request, int $id, string $action, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        // проверка на AJAX запрос
        if ($request->isXmlHttpRequest()) {
            $user = $userRepository->find($id);
            if (!$user) {
                // исключение можно и своё бросить...
                throw $this->createNotFoundException(
                    'Пользователь не найден, id ' . $id
                );
            }

            if ($action == 'enable') {
                $user->setEnabled();
                $em = $doctrine->getManager();
                $em->flush();
            }

//        return $this->redirectToRoute('users_sort', $request->query->all());
            $status = (int)$_POST['status'];
            $sortby =  $_POST['sortby']??null;
            $desc = $_POST['desc']??null;
            $users = $this->funcUsersSort($userRepository, $status, $sortby, $desc);
            if (($sortby !== null) and ($desc !== null)) {
                return $this->render('admin/_users_table.html.twig', [
                    'users' => $users,
                    'status' => $status,
                    'sortby' => $sortby,
                    'desc' => $desc,
                ]);
            }

            return $this->render('admin/_users_table.html.twig', [
                'users' => $users,
                'status' => $status,
            ]);
        }
        return $this->render('admin/_users_table.html.twig', [
            'users' => [],
            'status' => 0,
        ]);
    }

    #[Route('/mail', methods: ['GET'], name: 'admin_mail')]
    public function mail(MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $email = (new Email())
            ->from(new Address('admin@u146762.test-handyhost.ru'))
            //->to('vlevkovsky@mail.ru')
            ->to(new Address('admin@u146762.test-handyhost.ru'))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $logger->info('Щас отправлю...!');
            $mailer->send($email);
            $logger->info('Письмо отправлено!');
        } catch (TransportExceptionInterface $e) {
            $logger->error('Ошибка отправки почты!');
        }

        return $this->render('admin/base_adm.html.twig');
    }
}
