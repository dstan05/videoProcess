<?php

namespace App\Repository;

use App\Entity\ResizeVideo;
use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResizeVideo>
 *
 * @method ResizeVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResizeVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResizeVideo[]    findAll()
 * @method ResizeVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResizeVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResizeVideo::class);
    }

    public function add(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
