<?php


namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Handler\Form\CategoryHandler;
use App\Handler\Form\PostHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tricks')]
class AdminPostController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/new', name: 'app_admin_post_new')]
    public function new(Request $request, PostHandler $postHandler,): Response
    {
        $post = new Post();

        if ($postHandler->handle($request, $post)) {
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute(
                'tricks.show',
                $this->getParameters($post)
            );
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $postHandler->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_post_edit', methods: ['GET', 'POST'])]
    public function edit(Post $post, Request $request, PostHandler $postHandler,): Response
    {
        $this->denyAccessUnlessGranted('post_edit', $post);

        if ($postHandler->handle($request, $post)) {
            $this->addFlash('success', 'L\'article a bien été modifié avec succès');
            return $this->redirectToRoute(
                'tricks.show',
                $this->getParameters($post)
            );
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $postHandler->createView(),
            'current_page' => 'formPost'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_post_delete', methods: ['POST'])]
    public function delete(Post $post, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('post_delete', $post);

        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->get('_token'))) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'article a bien été supprimé avec succès');
        }
        return $this->redirectToRoute('app.home');
    }

    private function getParameters(Post $post): array
    {
        return [
            'slug' => $post->getSlug(),
            'id' => $post->getId()
        ];
    }
}