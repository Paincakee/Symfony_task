<?php

namespace App\Controller\projectManagement;

use App\Entity\Project;
use App\Entity\Status;
use App\Form\project\ProjectCreateType;
use App\Form\project\ProjectEditType;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    private $security;
    private ProjectService $projectService;
    public function __construct(Security $security, projectService $projectService)
    {
        $this->security = $security;
        $this->projectService = $projectService;
    }
    #[Route('/project', name: 'app_project_view')]
    public function index(): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $user = $this->getUser();
        $projects = $this->projectService->GetProjectsByUser($user);

        dump($projects);
        return $this->render('project/index.html.twig', [
            'title' => 'Projects',
            'icon' => 'folder2',
            'projects' => $projects,
        ]);
    }

    #[Route('/project-create', name: 'app_project_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $user = $this->getUser();

        $project = new Project();

        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUser($user);
            $status = $entityManager->getRepository(Status::class)->findOneBy(['name' => 'Under construction']);
            if (!$status) {
                throw $this->createNotFoundException('Status not found with name "under construction"');
            }

            $project->setStatus($status);
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_view');
        }

        return $this->render('project/create.html.twig', [
            'title' => 'Create Project',
            'icon' => 'plus-circle-dotted',
            'projectForm' => $form->createView(),
        ]);


    }
    #[Route('/project/{id}', name: 'app_project_detail')]
    public function view($id, ProjectRepository $projectRepository): Response
    {
        // Fetch the project by id
        $project = $this->projectService->getProjectById($id);

        // Render the project detail view
        return $this->render('project/detail.html.twig', [
            'project' => $project,
            'title' => 'Project: ' . $project->getName(),
            'icon' => 'folder2-open',
        ]);

    }
    #[Route('/project-edit/{id}', name: 'app_project_edit')]
    public function edit($id, ProjectRepository $projectRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');
        $project = $this->projectService->getProjectById($id);

        $form = $this->createForm(ProjectEditType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_view');
        }

        // Render the project detail view
        return $this->render('project/edit.html.twig', [
            'projectForm' => $form->createView(),
            'title' => 'Tasks',
            'icon' => 'columns-gap',
        ]);

    }

}
