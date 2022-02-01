<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PostRepository $repository
     * @return Response
     */
    public function index(PostRepository $repository): Response
    {
        $posts = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'current_menu' => 'home',
            'posts' => $posts
        ]);
    }
}
