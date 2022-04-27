<?php

namespace App\Helper;

use App\Entity\ImageBanner;

class ImageBannerUrlHelper
{
    /**
     * @param ImageBanner $entity
     * @return int|null
     */
    public function idHelper(ImageBanner $entity): ?int
    {
        return $entity->getPost()->getId();
    }

    /**
     * @param ImageBanner $entity
     * @return string|null
     */
    public function slugHelper(ImageBanner $entity): ?string
    {
        return $entity->getPost()->getSlug();
    }
}