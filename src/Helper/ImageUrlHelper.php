<?php

namespace App\Helper;

use App\Entity\Image;

 class ImageUrlHelper
{
    public function idHelper(Image $image): ?int
    {
        return $image->getPost()->getId();
    }

    public function slugHelper(Image $image): string
    {
        return $image->getPost()->getSlug();
    }

}