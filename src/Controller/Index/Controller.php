<?php

namespace App\Controller\Index;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(Request $request): Response
    {
        return $this->render('index/index.html.twig', ['user' => $request->get('user') ?: null]);
    }
}
