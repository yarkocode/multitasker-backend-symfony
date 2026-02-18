<?php

namespace App\Entity\Abstract;

use Doctrine\ORM\Mapping as ORM;

trait Auditable
{
    #[ORM\Column]
    private int $createdBy;

    #[ORM\Column]
    private int $lastModifiedBy;

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getLastModifiedBy(): int
    {
        return $this->lastModifiedBy;
    }

    public function setLastModifiedBy(int $lastModifiedBy): self
    {
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }
}
