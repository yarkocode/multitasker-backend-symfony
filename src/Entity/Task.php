<?php

namespace App\Entity;

use App\Entity\Abstract\Auditable;
use App\Entity\Abstract\AuditableInterface;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'tasks')]
class Task implements AuditableInterface
{
    use Auditable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['task:read', 'task:update', 'task:write'])]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['task:read', 'task:update', 'task:write'])]
    private ?string $description;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['task:read', 'task:update', 'task:write'])]
    private bool $completed = false;

    /**
     * Project as task location group (many-to-one relation)
     * @var Project|null in project located
     */
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn('project_id', 'id', nullable: true)]
    #[Groups(['task:read'])]
    #[Ignore]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;
        return $this;
    }
}
