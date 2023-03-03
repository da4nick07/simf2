<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestAjaxController extends AbstractController
{
    #[Route('/test/ajax', name: 'app_test_ajax')]
    public function index(): Response
    {
        if ( isset($_POST['sortby']) && ( isset($_POST['desc'])) ) {
            $sortby = $_POST['sortby'];
            $desc = $_POST['desc'];
        } else {
            $sortby = 'NO!';
            $desc = 'NO!';
        }
        return $this->render('test_ajax/index.html.twig', [
            'sortby' => $sortby,
            'desc' => $desc,
        ]);
    }
}
