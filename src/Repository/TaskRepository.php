<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry           $registry,
                                private readonly Security $security)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function findAllByCurrentUser()
    {
        return $this->createQueryBuilder('t')
            ->where('t.createdBy = :uid')
            ->setParameter('uid', $this->security->getUser()->getUserIdentifier())
            ->getQuery()
            ->getResult();
    }
}
