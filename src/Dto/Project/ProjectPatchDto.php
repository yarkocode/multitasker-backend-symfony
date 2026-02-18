<?php

namespace App\Dto\Project;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provide data to update project
 */
class ProjectPatchDto
{
    #[Assert\NotBlank(message: 'Name is required', allowNull: true)]
    protected ?string $name;

    #[Assert\NotBlank(message: 'Cant be blank. Allowed null', allowNull: true)]
    #[Assert\Length(min: 10, max: 10_000, minMessage: 'Minimum 10 characters', maxMessage: 'Maximum 10.000 characters')]
    protected ?string $description;

    /**
     * @param string $name
     * @param string|null $description
     */
    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
