<?php

namespace App\Controller;

use Exception;
use FFMpeg\FFMpeg;
use FOS\RestBundle\View\View;
use App\Service\FileUploader;
use InvalidArgumentException;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Handler\VideoResizeHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Video extends AbstractFOSRestController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    #[Route(path: '/video/', methods: 'POST')]
    public function add(
        Request $request,
        FileUploader $fileUploader,
        EntityManagerInterface $manager,
        MessageBusInterface $messageBus
    ): View {
        $files = $request->files->get('videos');
        if (empty($files)) {
            throw new InvalidArgumentException('Не передано видео');
        }
        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $videoEntity = new \App\Entity\Video();
            $fileUploader->upload($file);
            $videoEntity->setPath($fileUploader->getuploadPath())
                ->setName($file->getClientOriginalName());
            $manager->persist($videoEntity);

            try {
                $messageBus->dispatch(
                    new \App\Message\Command\Video($videoEntity->getId()));
            } catch (\Throwable $exception) {
                throw new Exception('Error buy product.');
            }
        }
        $ffmpeg =  FFMpeg::create();

        $videoFilters = $ffmpeg->open('sdf')->filters()->resize()->watermark()-Ю;

        $manager->flush();
        return $this->view(true);
    }

    #[Route(path: '/video/', methods: 'GET')]
    public function get(): View
    {
        return $this->view($this->videoRepository->findAll());
    }

    #[Route(path: '/video/{id}', methods: 'GET')]
    public function getById(int $id): View
    {
        $video = $this->videoRepository->findOneBy(['id' => $id]);
        if (empty($video)) {
            throw new NotFoundHttpException('Видео не найдено');
        }
        return $this->view($video);
    }
}
