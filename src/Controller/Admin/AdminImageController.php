<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Post;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/image')]
class AdminImageController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_admin_image_edit', methods: ['POST|GET'])]
    public function edit(
        Request                $request,
        Image                  $image,
        EntityManagerInterface $entityManager
    ): Response
    {
        $postId = $image->getPost()->getId();
        $postSlug = $image->getPost()->getSlug();

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image->setImageFilename($image->getImageFile());
            $entityManager->flush();
            $this->addFlash('success', 'L\'image a bien été modifiée avec succès');
            return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
        }

        return $this->render('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_image_delete', methods: ['DELETE'])]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $postId = $image->getPost()->getId();
        $postSlug = $image->getPost()->getSlug();

        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
            $this->addFlash('success', 'L\'image a bien été supprimée avec succès');
        }

        return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
    }
}
