<?php

namespace App\Controller;

use App\Service\File\FileUploader;
use App\Normalizer\VideoNormalizer;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use InvalidArgumentException;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route(path: '/videos/', methods: 'POST')]
    public function add(
        Request                $request,
        FileUploader           $fileUploader,
        EntityManagerInterface $manager,
        MessageBusInterface    $messageBus
    ): View
    {
        $files = $request->files->get('videos');
        if (empty($files)) {
            throw new InvalidArgumentException('Не передано видео');
        }
        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $videoEntity = new \App\Entity\Video();
            $fileName = $fileUploader->upload($file)
                ->fileName;
            $videoEntity->setPath($fileUploader->relativePath)
                ->setName($fileName);
            $manager->persist($videoEntity);
            try {
                $messageBus->dispatch(new \App\Message\Video($videoEntity->getId()));
            } catch (\Throwable $exception) {
                throw new $exception;
            }
        }
        $manager->flush();
        return $this->view(true);
    }

    #[Route(path: '/videos/', methods: 'GET')]
    public function get(): View
    {
        return $this->view($this->videoRepository->findAll());
    }

    #[Route(path: '/videos/{id}', methods: 'GET')]
    public function getById(int $id): View
    {
        $video = $this->videoRepository->findOneBy(['id' => $id]);
        if (empty($video)) {
            throw new NotFoundHttpException('Видео не найдено');
        }

        $viewContext = new Context();
        $viewContext->setGroups([VideoNormalizer::FULL]);
        return $this->view($video, 200)->setContext($viewContext);
    }
}
