<?php

namespace App\Dto\Task;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provide data to update task
 */
class TaskPatchDto
{
    #[Assert\NotBlank(message: 'Title cant be empty', allowNull: true)]
    protected ?string $title;

    #[Assert\NotBlank(message: 'Description cant be empty', allowNull: true)]
    #[Assert\Length(min: 10, max: 10_000, minMessage: 'Minimum 10 characters', maxMessage: 'Maximum 10.000 characters')]
    protected ?string $description;

    /**
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(?string $title, ?string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
