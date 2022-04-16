<?php

/**
 * Created by PhpStorm.
 * User: SAM Johnny
 * Date: 06/02/2022
 * Time: 09:27
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentService
{
    private EntityManagerInterface $entityManager;
    private FlashBagInterface $flashBag;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    public function persistCommentaire(Comment $comment, Post $post): void
    {
        $comment->setPost($post);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        $this->flashBag->add('success', 'Votre commentaire est bien envoyé');
    }
}