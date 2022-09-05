<?php

namespace App\Service;

use FFMpeg\FFMpeg;
use App\Entity\Video;

class VideoProcess
{
    private FFMpeg $ffmpeg;

    public function __construct(Video $video)
    {
        $this->ffmpeg = FFMpeg::create();
    }

    public function resize (): self
    {
        return $this;
    }

    public function setWatermark(): self
    {
        return $this;
    }

    public function save(): bool
    {
        return true;
    }
}
