<?php


namespace App\Controller\admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AbstractController
{
    private PostRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route ("/admin", name="admin.post.index")
     * @return Response
     */
    public function index(): Response
    {
        $posts = $this->repository->findAll();

        return $this->render('admin/post/index.html.twig', compact('posts'));
    }


    /**
     * @Route ("/admin/post/new", name="admin.post.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.post.index');
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route ("/admin/post/{id}", name="admin.post.edit", methods="GET|POST")
     * @param Post $post
     * @param Request $request
     * @return Response
     */
    public function edit(
        Post    $post,
        Request $request
    ): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('home');
        }
        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route ("/admin/post/{id}", name="admin.post.delete", methods="DELETE")
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(
        Post    $post,
        Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->get('_token'))) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('home');

    }

}