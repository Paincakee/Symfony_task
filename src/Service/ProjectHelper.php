<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProjectHelper
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager,){
        $this->entityManager = $entityManager;
    }

    public function GetProjectsByUser(User $user){
        return $this->entityManager->getRepository(Project::class)->findBy(['user' => $user]);
    }
    public function GetAllProjects(){
        return $this->entityManager->getRepository(Project::class)->findAll();
    }
}