<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/article/{slug}-{id}', name: 'post.show', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function show(
        Post                   $post,
        string                 $slug,
        Request                $request,
        EntityManagerInterface $entityManager,
        CommentRepository      $commentRepository
    ): Response
    {
        $getSlug = $post->getSlug();
        if ($getSlug !== $slug) {
            return $this->redirectToRoute('post.show', [
                'slug' => $getSlug,
                'id' => $post->getId()
            ], 301);
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire est bien envoyé');

            return $this->redirectToRoute('post.show', ['id' => $post->getId(), 'slug' => $post->getSlug()]);
        }
        return $this->render('post/show.html.twig', [
            'current_menu' => 'home',
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $commentRepository->findBy(['post' => $post])
        ]);
    }
}
