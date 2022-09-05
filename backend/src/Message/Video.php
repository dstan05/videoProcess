<?php

namespace App\Message;

class Video
{
    private int $videoId;

    public function __construct(int $videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->videoId;
    }
}
