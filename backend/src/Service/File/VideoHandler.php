<?php

namespace App\Service\File;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Audio\AudioFilters;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Video;

class VideoHandler
{
    private FFMpeg $ffmpeg;
    private Audio|Video $currentFile;
    private AudioFilters|VideoFilters $filters;
    private \App\Service\File\Video $video;

    public function __construct(\App\Service\File\Video $video)
    {
        $this->ffmpeg = FFMpeg::create();
        $this->video = $video;
        $this->currentFile = $this->ffmpeg->open($video->path . $video->name);
        $this->filters = $this->currentFile->filters();
    }

    public function resize (int $width, int $height): self
    {
        $this->filters->pad(new Dimension($width, $height));
        $this->filters->resize(new Dimension($width, $height));
        return $this;
    }

    public function setWatermark($imagePath, $coordinates): self
    {
        $this->filters->watermark($imagePath, $coordinates);
        return $this;
    }

    public function save(string $outputPathFile): bool
    {
        $this->currentFile->save(new X264(), $outputPathFile);
        return true;
    }
}
