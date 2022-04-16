<?php

namespace App\Helper;

use App\Entity\LinkMedia;

class LinkMediaUrlHelper
{
    public function idHelper(LinkMedia $linkMedia): ?int
    {
        return $linkMedia->getPost()->getId();
    }

    public function slugHelper(LinkMedia $linkMedia): ?int
    {
        return $linkMedia->getPost()->getSlug();
    }

}