<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategorieController extends AbstractController
{

    #[Route('/admin/categorie/new', name: 'categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('app.home');
        }

        return $this->renderForm('admin/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

}
