<?php

namespace App\MessageHandler;

use App\Entity\ResizeVideo;
use App\Message\Video;
use App\Repository\VideoRepository;
use App\Service\File\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class VideoHandler implements MessageHandlerInterface
{
    private array $config;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly VideoRepository        $videoRepository,
        private readonly ContainerBagInterface  $configurator,
        private readonly EntityManagerInterface $manager
    )
    {
        $this->config = $this->configurator->get('video_handler');
    }

    public function __invoke(Video $video)
    {
        $videoEntity = $this->videoRepository->findOneBy(['id' => $video->getId()]);
        if (!$videoEntity) {
            return;
        }
        $video = new \App\Service\File\Video($videoEntity->getName(), $videoEntity->getPath());
        foreach ($this->config['watermark'] as $key => $watermark) {
            if (empty($watermark['image_path']) || !file_exists($watermark['image_path'])) {
                continue;
            }
            foreach ($this->config['size'] as $quality => $size) {
                $videoHandler = new \App\Service\File\VideoHandler($video);
                if ($video->getDimension()->getHeight() < $quality) {
                    continue 2;
                }
                $videoHandler->resize($size['width'], $size['height']);
                $videoHandler->setWatermark($watermark['image_path'], (array)$watermark['coordinates']);
                $name = $video->getFileName() . '_' . $quality . '_' . $key . '.' . $video->getType();
                $videoHandler->save($this->config['save_path'] . $name);

                $resizedVideo = new ResizeVideo();
                $resizedVideo->video = $videoEntity;
                $resizedVideo->path = FileUploader::getRelativePath(
                    $this->configurator->get('public_directory'),
                    $this->config['save_path'] . $name
                );
                $resizedVideo->quality = $quality;
                $this->manager->persist($resizedVideo);
            }
        }
        $this->manager->flush();
    }
}
