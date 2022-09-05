<?php
namespace App\MessageHandler;

use App\Message\Video;
use App\Service\VideoProcess;
use App\Repository\VideoRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class VideoHandler implements MessageHandlerInterface
{
    public function __invoke(Video $video, VideoRepository $videoRepository)
    {
        $videoEntity = $videoRepository->findOneBy(['id' => $video->getId()]);
        if (!$videoEntity) {
            return;
        }
        $videoProcess = new VideoProcess($videoEntity);
        $result = $videoProcess->resize()->setWatermark()->save();
    }

}
