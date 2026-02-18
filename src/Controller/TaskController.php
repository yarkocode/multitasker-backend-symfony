<?php

namespace App\Controller;

use App\Dto\Task\TaskCreateDto;
use App\Dto\Task\TaskPatchDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * API Controller for user tasks management
 * @extends AbstractController
 */
#[Route(path: '/tasks')]
final class TaskController extends AbstractController
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }


    /**
     * Get all tasks created by user
     *
     * @return JsonResponse<Task[]> list of tasks
     */
    #[Route(methods: ['GET'])]
    public function getAllTasks(): JsonResponse
    {
        return $this->json($this->taskRepository->findAllByCurrentUser());
    }

    /**
     * Create task and return task json repr.
     *
     * @param TaskCreateDto $taskCreateDto task data to store
     * @return JsonResponse<Task> created task json representation
     */
    #[Route(methods: ['POST'])]
    public function createTask(
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        TaskCreateDto $taskCreateDto
    ): JsonResponse
    {
        $task = new Task()
            ->setTitle($taskCreateDto->getTitle())
            ->setDescription($taskCreateDto->getDescription());

        $this->taskRepository->save($task);

        return $this->json($task);
    }

    /**
     * Get task by id and return task json repr.
     *
     * @param Task $task automatic mapped task entity by taskId path param
     * @return JsonResponse<Task> task json representation
     */
    #[Route(path: '/{taskId}', methods: ['GET'])]
    public function getTaskById(
        #[MapEntity(id: 'taskId', message: 'The task doesnt exists')]
        Task $task
    ): JsonResponse
    {
        return $this->json($task);
    }

    /**
     * Patch-update task and return updated task json repr.
     *
     * @param Task $task automatic mapped task entity by taskId path param
     * @param TaskPatchDto $taskPatchDto updates for task
     * @return JsonResponse<Task> updated task json representation
     */
    #[Route(path: '/{taskId}', methods: ['PATCH'])]
    public function updateTaskById(
        #[MapEntity(id: 'taskId', message: 'The task doesnt exists')] Task                       $task,
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)] TaskPatchDto $taskPatchDto
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
     * @return JsonResponse<Task> updated task json representation
     *
     * @throws ExceptionInterface on serialize patch dto to json back
     */
    #[Route(path: '/{taskId}', methods: ['PATCH'])]
    public function updateTaskById(
        #[MapEntity(id: 'taskId', message: 'The task doesnt exists')]
        Task                $task,
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        TaskPatchDto        $taskPatchDto,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        $patchedFields = $serializer->serialize($taskPatchDto, 'json', [
            'skip_null_values' => true
        ]);
        $serializer->deserialize($patchedFields, Task::class, 'json', [
            'groups' => ['task:update'],
            'object_to_populate' => $task,
        ]);

        $this->taskRepository->save($task);

        return $this->json($task);
    }
}
