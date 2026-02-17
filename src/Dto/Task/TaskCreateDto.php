<?php

namespace App\Dto\Task;

/**
 * Provide data to create task
 */
class TaskCreateDto
{
    protected string $title;
    protected ?string $description;
}
