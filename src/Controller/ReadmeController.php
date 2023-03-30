<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadmeController extends AbstractController
{
    #[Route('/readme', methods: ['GET'], name: 'app_readme')]
    public function index(): Response
    {
        return $this->render('readme/index.html.twig', [
            'content_title' => 'README',
            'readme' => file('../README.txt'),
        ]);
    }
}
