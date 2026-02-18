<?php

namespace App\Entity\EventListener;

use App\Entity\Abstract\AuditableInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM as ORM;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: ORM\Events::prePersist)]
#[AsDoctrineListener(event: ORM\Events::preUpdate)]
readonly class AuditEventListener
{
    public function __construct(
        private ?Security $security = null
    )
    {
    }

    public function prePersist(PrePersistEventArgs $event): void
    {
        $entity = $event->getObject();
        if (!$entity instanceof AuditableInterface) {
            return;
        }

        $userId = $this->security->getUser()?->getUserIdentifier();
        if ($userId) {
            $entity->setCreatedBy($userId);
            $entity->setLastModifiedBy($userId);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        if (!$entity instanceof AuditableInterface) {
            return;
        }

        $userId = $this->security->getUser()?->getUserIdentifier();
        if ($userId) {
            $entity->setLastModifiedBy($userId);
        }
    }
}
