<?php

namespace App\Controller;

use App\Dto\Task\TaskCreateDto;
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

#[Route('/projects/{projectId}/tasks')]
final class ProjectTasksController extends AbstractController
{
    public function __construct(private readonly TaskService       $taskService,
                                private readonly ProjectRepository $projectRepository)
    {
    }

    /**
     * Create task and add to project
     *
     * @param Project $project project where task will located
     * @param TaskCreateDto $taskCreateDto data to create task in project
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
}
