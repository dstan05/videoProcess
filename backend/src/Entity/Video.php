<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: 'video', schema: 'public')]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'video', targetEntity: ResizeVideo::class, orphanRemoval: true)]
    public Collection $resizedVideos;

    public function __construct()
    {
        $this->resizedVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ResizeVideo>
     */
    public function getResizedVideos(): Collection
    {
        return $this->resizedVideos;
    }

    public function addResizedVideo(ResizeVideo $resizedVideo): self
    {
        if (!$this->resizedVideos->contains($resizedVideo)) {
            $this->resizedVideos->add($resizedVideo);
            $resizedVideo->setVideo($this);
        }

        return $this;
    }

    public function removeResizedVideo(ResizeVideo $resizedVideo): self
    {
        if ($this->resizedVideos->removeElement($resizedVideo)) {
            // set the owning side to null (unless already changed)
            if ($resizedVideo->getVideo() === $this) {
                $resizedVideo->setVideo(null);
            }
        }

        return $this;
    }
}
