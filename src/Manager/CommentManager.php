<?php
namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentManager
{
    protected $entityManager;
    protected $messageService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag

    )
    {

    }
}