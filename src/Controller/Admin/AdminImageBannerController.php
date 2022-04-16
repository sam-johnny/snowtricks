<?php

namespace App\Controller\Admin;

use App\Entity\ImageBanner;
use App\Form\ImageBannerType;
use App\Helper\ImageBannerUrlHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image/banner')]
class AdminImageBannerController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_image_banner_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        ImageBanner            $imageBanner,
        ImageBannerUrlHelper $bannerUrlHelper,
        EntityManagerInterface $entityManager
    ): Response
    {
        $postId = $bannerUrlHelper->idHelper($imageBanner);
        $postSlug = $bannerUrlHelper->slugHelper($imageBanner);

        $form = $this->createForm(ImageBannerType::class, $imageBanner);
        $form->handleRequest($request);

        $imageBanner->getPost()->getImageBanner();
        if ($form->isSubmitted() && $form->isValid()) {
            $imageBanner->setImageFilename($imageBanner->getImageFile());
            $entityManager->flush();
            $this->addFlash('success', 'L\'image a bien été modifiée avec succès');
            return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
        }

        return $this->renderForm('admin/image_banner/edit.html.twig', [
            'image_banner' => $imageBanner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_banner_delete', methods: ['POST'])]
    public function delete(
        Request                $request,
        ImageBanner            $imageBanner,
        ImageBannerUrlHelper $imageBannerUrlHelper,
        EntityManagerInterface $entityManager
    ): Response
    {
        $postId = $imageBannerUrlHelper->idHelper($imageBanner);
        $postSlug = $imageBannerUrlHelper->slugHelper($imageBanner);

        if ($this->isCsrfTokenValid('delete' . $imageBanner->getId(), $request->request->get('_token'))) {
            $entityManager->remove($imageBanner);
            $entityManager->flush();
            $this->addFlash('success', 'L\'image a bien été supprimée avec succès');
        }

        return $this->redirectToRoute('tricks.show', ['slug' => $postSlug, 'id' => $postId]);
    }
}
