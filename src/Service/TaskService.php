<?php

namespace App\Service;

use App\Dto\Task\TaskCreateDto;
use App\Entity\Task;
use App\Repository\TaskRepository;

readonly class TaskService
{

    public function __construct(private TaskRepository $taskRepository)
    {
    }

    public function createTask(TaskCreateDto $taskCreateDto): Task
    {
        $task = new Task()
            ->setTitle($taskCreateDto->getTitle())
            ->setDescription($taskCreateDto->getDescription());

        $this->taskRepository->save($task);
        return $task;
    }
}
