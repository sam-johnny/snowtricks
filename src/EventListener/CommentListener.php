<?php

/**
 * Created by PhpStorm.
 * User: SAM Johnny
 * Date: 25/04/2022
 * Time: 15:03
 */

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CommentListener
{
    protected TokenStorageInterface $storage;

    /**
     * @param TokenStorageInterface $storage
     */
    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Comment) {
            $user = $this->storage->getToken()->getUser();
            $entity->setUser($user);
        }
    }


}

