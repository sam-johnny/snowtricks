<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private CommentRepository $commentRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        CommentRepository  $commentRepository,
        PaginatorInterface $paginator
    )
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }


    #[Route('/tricks/details/{slug}-{id}', name: 'tricks.show', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function show(
        Post                   $post,
        Request                $request,
        string                 $slug,
        EntityManagerInterface $entityManager
    ): Response
    {

        $getSlug = $post->getSlug();
        if ($getSlug !== $slug) {
            return $this->redirectToRoute('tricks.show', [
                'slug' => $getSlug,
                'id' => $post->getId()
            ], 301);
        }

        $comments = $this->paginator->paginate(
            $this->commentRepository->findBy(['post' => $post], ['created_at' => 'DESC']),
            $request->query->getInt('page', 1),
            10
        );

        $commentsCount = count($this->commentRepository->findBy(['post' => $post], ['created_at' => 'DESC']));

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire est bien envoyé');

            return $this->redirectToRoute('tricks.show', ['id' => $post->getId(), 'slug' => $post->getSlug()]);
        }

        return $this->render('post/show.html.twig', [
            'current_menu' => 'tricks',
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $comments,
            'commentsCount' => $commentsCount
        ]);
    }

    #[Route('/loadmoreComments/{id}', name: 'comment.loadmore')]
    public function loadMoreComment
    (
        Post    $post,
        Request $request

    ): Response
    {
        $comments = $this->paginator->paginate(
            $this->commentRepository->findBy(['post' => $post], ['created_at' => 'DESC']),
            $request->query->getInt('page'),
            10
        );

        return $this->render('comment/_loadMore.html.twig', [
            'comments' => $comments
        ]);
    }


}
