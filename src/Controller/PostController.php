<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    /**
     * @Route("/post/{slug}-{id}", name="post.show")
     * @return Response
     */
    public function show(): Response
    {

        return $this->render('post/show.html.twig', [

        ]);
    }
}