<?php

namespace App\Controller\Admin;

use App\Entity\ImageBanner;
use App\Form\ImageBannerType;
use App\Repository\ImageBannerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image/banner')]
class AdminImageBannerController extends AbstractController
{
    #[Route('/new', name: 'app_image_banner_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ImageBannerRepository $imageBannerRepository,
        ImageBanner $imageBanner
    ): Response
    {
        $postId = $imageBanner->getPost()->getId();

        $imageBanner = new ImageBanner();
        $form = $this->createForm(ImageBannerType::class, $imageBanner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageBannerRepository->add($imageBanner);
            return $this->redirectToRoute('app_admin_post_edit', ['id' => $postId]);
        }

        return $this->renderForm('image_banner/new.html.twig', [
            'image_banner' => $imageBanner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_banner_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request       $request,
        ImageBanner   $imageBanner,
        EntityManagerInterface $entityManager
    ): Response
    {
        $postId = $imageBanner->getPost()->getId();
        $postSlug = $imageBanner->getPost()->getSlug();

        $form = $this->createForm(ImageBannerType::class, $imageBanner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageBanner->setImageFilename($imageBanner->getImageFile());
            $entityManager->flush();
            $this->addFlash('success', 'L\'image a bien été modifiée avec succès');
            return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
        }

        return $this->renderForm('image_banner/edit.html.twig', [
            'image_banner' => $imageBanner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_banner_delete', methods: ['POST'])]
    public function delete(
        Request               $request,
        ImageBanner           $imageBanner,
        ImageBannerRepository $imageBannerRepository
    ): Response
    {
        $postId = $imageBanner->getPost()->getId();
        $postSlug = $imageBanner->getPost()->getSlug();

        if ($this->isCsrfTokenValid('delete' . $imageBanner->getId(), $request->request->get('_token'))) {
            $imageBannerRepository->remove($imageBanner);
        }

        return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
    }
}
