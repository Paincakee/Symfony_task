<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProjectService
{
    private ProjectRepository $projectRepository;
    public function __construct(ProjectRepository $projectRepository){
        $this->projectRepository = $projectRepository;
    }

    public function GetProjectsByUser(User $user): array
    {
        return $this->projectRepository->findBy(['user' => $user]);
    }
    public function GetAllProjects(): array
    {
        return $this->projectRepository->findAll();
    }

    public function getProjectById($id): Project
    {
        $project = $this->projectRepository->find($id);

        if (!$project) return throw new NotFoundHttpException('Project not found');

        return $project;
    }

    public function getUsersById($id): array
    {
        $project = $this->projectRepository->find($id);

        $users = $project->getUser();

        if (!$project) return throw new NotFoundHttpException('Project not found');

        return $project;
    }
}