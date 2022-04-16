<?php

namespace App\Helper;

use App\Entity\ImageBanner;

class ImageBannerUrlHelper
{
    public function idHelper(ImageBanner $entity): ?int
    {
        return $entity->getPost()->getId();
    }

    public function slugHelper(ImageBanner $entity): string
    {
        return $entity->getPost()->getSlug();
    }

}