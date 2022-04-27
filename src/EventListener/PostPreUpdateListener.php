<?php

namespace App\EventListener;

use App\Entity\Post;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PostPreUpdateListener
{
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Post) {
            $entity->setUpdatedAt(new \DateTimeImmutable('now'));
        }
    }
}