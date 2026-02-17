<?php

namespace App\Controller;

use App\Dto\Task\TaskCreateDto;
use App\Dto\Task\TaskDto;
use App\Dto\Task\TaskPatchDto;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;


/**
 * API Controller for user tasks management
 * @extends AbstractController
 */
#[Route(path: '/tasks')]
final class TaskController extends AbstractController
{
    /**
     * Get all tasks created by user
     *
     * @return JsonResponse<TaskDto[]> list of tasks
     */
    #[Route(path: '/', methods: ['GET'])]
    public function getAllTasks(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }

    /**
     * Create task and return task json repr.
     *
     * @param TaskCreateDto $taskCreateDto task data to store
     * @return JsonResponse<TaskDto> created task json representation
     */
    #[Route('/', methods: ['POST'])]
    public function createTask(#[MapRequestPayload] TaskCreateDto $taskCreateDto): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }

    /**
     * Get task by id and return task json repr.
     *
     * @param Task $task automatic mapped task entity by taskId path param
     * @return JsonResponse<TaskDto> task json representation
     */
    #[Route(path: '/{taskId}')]
    public function getTaskById(
        #[MapEntity(id: 'taskId', message: 'The task doesnt exists')]
        Task $task
    ): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }

    /**
     * Patch-update task and return updated task json repr.
     *
     * @param Task $task automatic mapped task entity by taskId path param
     * @param TaskPatchDto $taskPatchDto updates for task
     * @return JsonResponse<TaskDto> updated task json representation
     */
    #[Route(path: '/{taskId}', methods: ['PATCH'])]
    public function updateTaskById(
        #[MapEntity(id: 'taskId', message: 'The task doesnt exists')]
        Task                              $task,
        #[MapRequestPayload] TaskPatchDto $taskPatchDto
    ): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }
}
