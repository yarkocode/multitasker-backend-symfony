<?php

namespace App\Dto\Task;

/**
 * Returned in response in TaskController
 * @extends TaskCreateDto
 */
class TaskDto extends TaskCreateDto
{
    protected int $id;
    protected int $createdBy;
    protected int $lastModifiedBy;
}
