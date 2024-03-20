<?php

namespace App\Controller\projectManagement;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProjectHelper;

class ProjectController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/project', name: 'app_project_view')]
    public function index(projectHelper $projectHelper): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $user = $this->getUser();
        $projects = $projectHelper->GetProjectsByUser($user);

        return $this->render('project/index.html.twig', [
            'title' => 'Tasks',
            'icon' => 'columns-gap',
            'projects' => $projects,
        ]);
    }

    #[Route('/project-create', name: 'app_project_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

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
    #[Route('/project/{id}', name: 'app_project_detail')]
    public function view($id, ProjectRepository $projectRepository): Response
    {
        // Fetch the project by id
        $project = $projectRepository->find($id);

        // Check if the project exists
        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        // Render the project detail view
        return $this->render('project/view.html.twig', [
            'project' => $project,
            'title' => 'Tasks',
            'icon' => 'columns-gap',
        ]);

    }

}
