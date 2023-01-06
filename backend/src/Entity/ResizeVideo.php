<?php

namespace App\Entity;

use App\Repository\ResizeVideoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResizeVideoRepository::class)]
#[ORM\Table(name: 'resize_video', schema: 'public')]
class ResizeVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $path;
    #[ORM\Column(length: 255)]
    public int $quality;

    #[ORM\ManyToOne(inversedBy: 'resizedVideos')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Video $video = null;
}
