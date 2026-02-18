<?php

namespace App\Service;

use App\Dto\Task\TaskCreateDto;
use App\Dto\Task\TaskPatchDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class TaskService
{

    public function __construct(private TaskRepository               $taskRepository,
                                private readonly SerializerInterface $serializer)
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

    /**
     * @throws ExceptionInterface on serialize back patch dto to json
     */
    public function updateTask(Task $task, TaskPatchDto $taskPatchDto): void
    {
        $patchedFields = $this->serializer->serialize($taskPatchDto, 'json', [
            'skip_null_values' => true
        ]);
        $this->serializer->deserialize($patchedFields, Task::class, 'json', [
            'groups' => ['task:update'],
            'object_to_populate' => $task,
        ]);

        $this->taskRepository->save($task);
    }
}
