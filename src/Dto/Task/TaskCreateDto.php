<?php

namespace App\Dto\Task;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provide data to create task
 */
class TaskCreateDto
{
    #[Assert\NotBlank(message: 'Title is required')]
    protected string $title;

    #[Assert\NotBlank(message: 'Cant be blank. Allowed null', allowNull: true)]
    #[Assert\Length(min: 10, max: 10_000, minMessage: 'Minimum 10 characters', maxMessage: 'Maximum 10.000 characters')]
    protected ?string $description;

    /**
     * @param string $title
     * @param string|null $description
     */
    public function __construct(string $title, ?string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
