<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private PostRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(
        PostRepository     $repository,
        PaginatorInterface $paginator
    )
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app.home')]
    public function index(
        Request $request
    ): Response
    {
        $posts = $this->paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        $postsCount = count($this->repository->findAll());

        return $this->render('home/index.html.twig', [
            'current_menu' => 'home',
            'posts' => $posts,
            'postsCount' => $postsCount
        ]);
    }

    #[Route('/loadmorePosts', name: 'post.loadmore')]
    public function loadMorePost(Request $request): Response
    {
        $posts = $this->paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page'),
            10
        );

        return $this->render('home/_loadMore.html.twig', [
            'posts' => $posts
        ]);
    }
}
