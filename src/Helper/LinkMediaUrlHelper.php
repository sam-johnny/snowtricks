<?php

namespace App\Helper;

use App\Entity\LinkMedia;

class LinkMediaUrlHelper
{
    /**
     * @param LinkMedia $linkMedia
     * @return int|null
     */
    public function idHelper(LinkMedia $linkMedia): ?int
    {
        return $linkMedia->getPost()->getId();
    }

    /**
     * @param LinkMedia $linkMedia
     * @return string|null
     */
    public function slugHelper(LinkMedia $linkMedia): ?string
    {
        return $linkMedia->getPost()->getSlug();
    }
}