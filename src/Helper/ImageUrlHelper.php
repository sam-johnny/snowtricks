<?php

namespace App\Helper;

use App\Entity\Image;

 class ImageUrlHelper
{
     /**
      * @param Image $image
      * @return int|null
      */
     public function idHelper(Image $image): ?int
    {
        return $image->getPost()->getId();
    }

     /**
      * @param Image $image
      * @return string|null
      */
     public function slugHelper(Image $image): ?string
    {
        return $image->getPost()->getSlug();
    }
}