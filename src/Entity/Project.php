<?php

namespace App\Entity;

use App\Entity\Abstract\Auditable;
use App\Entity\Abstract\AuditableInterface;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'projects')]
class Project implements AuditableInterface
{
    use Auditable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['project:update', 'project:create'])]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['project:update', 'project:create'])]
    private ?string $description = null;

    /**
     * Collection of project tasks (one-to-many relation)
     * @var Collection<Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
    private Collection $tasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    /**
     * Get tasks collection (no db context)
     * @return Collection not associated without db cursor context
     */
    public function getTasks(): Collection
    {
        return new ArrayCollection($this->tasks->toArray());
    }

    /**
     * Add task to collection
     * @param Task $task
     * @return self
     */
    public function addTask(Task $task): self
    {
        $this->tasks->add($task);
        return $this;
    }
}
