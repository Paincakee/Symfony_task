<?php

namespace App\Controller\projectManagement;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project_view')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'title' => 'Tasks',
            'icon' => 'columns-gap',
            'projects' => $projects,
        ]);
    }

    #[Route('/project-create', name: 'app_project_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUser($user);

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_view');
        }

        return $this->render('project/create.html.twig', [
            'title' => 'Create task',
            'icon' => 'plus-circle-dotted',
            'projectForm' => $form->createView(),
        ]);
    }
}
