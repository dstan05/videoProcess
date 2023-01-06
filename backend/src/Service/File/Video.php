<?php

namespace App\Service\File;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFProbe;

class Video
{
    public string $path;
    public string $name;
    private ?FFProbe\DataMapping\Stream $streame;

    public function __construct(string $name, string $path)
    {
        $this->path = $path;
        $this->name = $name;
        $this->streame = FFProbe::create()->streams($this->path . $this->name)->first();
        $this->checkVideo();
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->streame->getDimensions();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)pathinfo($this->path . $this->name)['extension'];
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return (string)pathinfo($this->path . $this->name)['filename'];
    }

    /**
     * @return void
     */
    private function checkVideo(): void
    {
        if (!$this->streame || !$this->streame->isVideo()) {
            throw new \InvalidArgumentException('File not video');
        }
        if (!file_exists($this->path . $this->name)) {
            throw new \InvalidArgumentException('File not found');
        }
    }
}
