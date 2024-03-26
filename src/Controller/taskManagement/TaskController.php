<?php

namespace App\Controller\taskManagement;

use App\Entity\Task;
use App\Form\task\TaskCreateType;
use App\Form\task\TaskUpdateType;
use App\Repository\TaskRepository;
use App\Service\ProjectService;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    private $security;
    private ProjectService $projectService;
    private TaskService $taskService;
    public function __construct(Security $security, ProjectService $projectService, TaskService $taskService)
    {
        $this->security = $security;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }
    #[Route('/task/{id}', name: 'app_task_view')]
    public function index($id): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $task = $this->taskService->getTaskById($id);

        if (!$task) return throw new NotFoundHttpException('Task not found.');

        return $this->render('task/detail.html.twig', [
            'title' => $task->getName(),
            'icon' => 'columns-gap',
            'task' => $task
        ]);
    }

    #[Route('/task-create/{id}', name: 'app_task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $task = new Task();

        $form = $this->createForm(TaskCreateType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the current user
            $user = $this->getUser();

            $task->setUser($user);
            $project = $this->projectService->getProjectById($id);
            $task->setProject($project);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_detail', ['id' => $id]);
        }

        return $this->render('task/create.html.twig', [
            'title' => 'Create task',
            'icon' => 'plus-circle-dotted',
            'taskForm' => $form->createView(),
        ]);
    }

    #[Route('/task-edit/{id}', name: 'app_task_edit')]
    public function update(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $user = $this->getUser();

        $task = $this->taskService->getTaskById($id);

        if ($task->getUser()!== $user && $task->getProject()->getUser() !== $user) throw $this->createNotFoundException('You are not authorized to update this task.');

        $form = $this->createForm(TaskUpdateType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_detail', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'title' => 'Update task',
            'icon' => 'pencil',
            'taskForm' => $form->createView(),
        ]);
    }
}
