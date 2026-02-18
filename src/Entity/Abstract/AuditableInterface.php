<?php

namespace App\Entity\Abstract;

interface AuditableInterface
{
    public function getCreatedBy(): int;

    public function setCreatedBy(int $createdBy): self;

    public function getLastModifiedBy(): int;

    public function setLastModifiedBy(int $lastModifiedBy): self;
}
