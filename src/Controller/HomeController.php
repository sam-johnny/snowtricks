<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $repository): Response
    {
        $posts = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'current_menu' => 'home',
            'posts' => $posts
        ]);
    }
}
