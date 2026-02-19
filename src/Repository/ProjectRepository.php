<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(private readonly Security $security,
                                ManagerRegistry           $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findAllByCurrentUser()
    {
        return $this->createQueryBuilder('p')
            ->where('p.createdBy = :uid')
            ->setParameter('uid', $this->security->getUser()->getUserIdentifier())
            ->getQuery()
            ->getResult();
    }

    public function save(Project $project): void
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();
    }
}
