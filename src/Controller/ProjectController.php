<?php

namespace App\Controller;

use App\Dto\Project\ProjectCreateDto;
use App\Dto\Project\ProjectPatchDto;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * API Controller for user projects management
 * @extends AbstractController
 */
#[Route('/projects')]
final class ProjectController extends AbstractController
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }

    /**
     * @return JsonResponse<Project[]>
     */
    #[Route(methods: ['GET'])]
    public function getProjectsWhereUserIsMember(): JsonResponse
    {
        return $this->json([
            'status' => 'Coming soon'
        ]);
    }

    /**
     * Get user created projects
     *
     * @return JsonResponse<Project[]>
     */
    #[Route('/my', methods: ['GET'])]
    public function getProjectsCreatedByAuthenticatedUser(): JsonResponse
    {
        return $this->json($this->projectRepository->findAllByCurrentUser());
    }

    /**
     * Create new project
     *
     * @return JsonResponse<Project>
     */
    #[Route(methods: ['POST'])]
    public function createProject(
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        ProjectCreateDto $projectCreateDto
    ): JsonResponse
    {
        $project = new Project()
            ->setName($projectCreateDto->getName())
            ->setDescription($projectCreateDto->getDescription());
        $this->projectRepository->save($project);

        return $this->json($project);
    }

    /**
     * Get own project by id and return project json repr.
     *
     * @param Project $project automatic mapped project entity by projectId path param
     * @return JsonResponse<Project> project json representation
     */
    #[Route(path: '/{projectId}', methods: ['GET'])]
    public function getProjectById(
        #[MapEntity(id: 'projectId', message: 'The project doesnt exists')]
        Project $project
    ): JsonResponse
    {
        return $this->json($project);
    }

    /**
     * Patch-update own project and return updated project json repr.
     *
     * @param Project $project automatic mapped project entity by projectId path param
     * @param ProjectPatchDto $projectPatchDto updates for project
     * @return JsonResponse<Project> updated project json representation
     *
     * @throws ExceptionInterface on serialize patch dto to json back
     */
    #[Route(path: '/{projectId}', methods: ['PATCH'])]
    public function updateProjectById(
        #[MapEntity(id: 'projectId', message: 'The project doesnt exists')]
        Project             $project,
        #[MapRequestPayload(acceptFormat: 'json', validationFailedStatusCode: 400)]
        ProjectPatchDto     $projectPatchDto,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        $patchedFields = $serializer->serialize($projectPatchDto, 'json', [
            'skip_null_values' => true
        ]);
        $serializer->deserialize($patchedFields, Project::class, 'json', [
            'groups' => ['project:update'],
            'object_to_populate' => $project,
        ]);

        $this->projectRepository->save($project);

        return $this->json($project);
    }
}
