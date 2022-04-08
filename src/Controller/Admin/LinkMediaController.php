<?php

namespace App\Controller\Admin;

use App\Entity\LinkMedia;
use App\Form\LinkMediaType;
use App\Repository\LinkMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/link/media')]
class LinkMediaController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_link_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LinkMedia $linkMedia, LinkMediaRepository $linkMediaRepository): Response
    {
        $form = $this->createForm(LinkMediaType::class, $linkMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linkMediaRepository->add($linkMedia);
            return $this->redirectToRoute('app_link_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('link_media/edit.html.twig', [
            'link_media' => $linkMedia,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_media_delete', methods: ['POST'])]
    public function delete(Request $request, LinkMedia $linkMedia, LinkMediaRepository $linkMediaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$linkMedia->getId(), $request->request->get('_token'))) {
            $linkMediaRepository->remove($linkMedia);
        }

        return $this->redirectToRoute('app_link_media_index', [], Response::HTTP_SEE_OTHER);
    }
}
