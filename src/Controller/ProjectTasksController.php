<?php

namespace App\Controller;

use App\Dto\Task\TaskCreateDto;
use App\Dto\Task\TaskPatchDto;
use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\TaskService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * API Controller to manage project tasks
 * @extends AbstractController
 */
#[Route('/projects/{projectId}/tasks')]
final class ProjectTasksController extends AbstractController
{
    public function __construct(private readonly TaskService       $taskService,
                                private readonly ProjectRepository $projectRepository)
    {
    }

    /**
     * Get all project tasks
     *
     * @param Project $project user workplace with tasks
     * @return JsonResponse<Task[]> project tasks
     */
    #[Route(methods: ['GET'])]
    public function getAllTasks(#[MapEntity(id: 'projectId')] Project $project): JsonResponse
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW_TASKS, $project);
        return $this->json($project->getTasks());
    }

    /**
     * Create task and add to project
     *
     * @param Project $project project where task will locate
     * @param TaskCreateDto $taskCreateDto data to create task
     * @return JsonResponse<Task> created task
     */
    #[Route(methods: ['POST'])]
    public function createTask(
        #[MapEntity(id: 'projectId')]
        Project       $project,
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        TaskCreateDto $taskCreateDto
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ProjectVoter::CREATE_TASKS, $project);
        $task = $this->taskService->createTask($taskCreateDto);
        $project->addTask($task);

        $this->projectRepository->save($project);

        return $this->json($task);
    }

    /**
     * Update task in project where is located
     *
     * @param Project $project project where located task
     * @param Task $task task to update
     * @param TaskPatchDto $taskPatchDto data to update task
     * @return JsonResponse<Task> updated task
     * @throws ExceptionInterface on serialize back patch dto to json ($taskService->updateTask)
     */
    #[Route('/{taskId}', methods: ['PATCH'])]
    public function updateTask(
        #[MapEntity(id: 'projectId')]
        Project      $project,
        #[MapEntity(id: 'taskId')]
        Task         $task,
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        TaskPatchDto $taskPatchDto
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ProjectVoter::UPDATE_TASKS, $project);
        $this->taskService->updateTask($task, $taskPatchDto);
        return $this->json($task);
    }
}
