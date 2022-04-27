<?php

namespace App\Entity;

use App\Repository\LinkMediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkMediaRepository::class)]
class LinkMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $url;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'linkMedia')]
    #[ORM\JoinColumn(nullable: false)]
    private $post;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getRegLink(): string
    {
        $regex = "/=([\w-]*)/";
            preg_match($regex, $this->getUrl(), $matches);
            return $matches[1];
    }
}
