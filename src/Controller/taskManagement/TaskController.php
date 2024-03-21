<?php

namespace App\Controller\taskManagement;

use App\Entity\Task;
use App\Form\task\TaskCreateType;
use App\Form\task\TaskUpdateType;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/task/{id}', name: 'app_task_view')]
    public function index(TaskService $taskService, $id, ): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $task = $taskService->getTaskById($id);

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

        // Get the current user
        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskCreateType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);

            $task->setDate(new \DateTime());
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_view');
        }

        return $this->render('task/create.html.twig', [
            'title' => 'Create task',
            'icon' => 'plus-circle-dotted',
            'taskForm' => $form->createView(),
        ]);
    }

    #[Route('/task-update/{id}', name: 'app_task_update')]
    public function update(EntityManagerInterface $entityManager, Request $request, $id, TaskService $taskService): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $user = $this->getUser();

        $task = $taskService->getTaskById($id);

        if ($task->getUser()!== $user && $task->getProject()->getUser() !== $user) throw $this->createNotFoundException('You are not authorized to update this task.');

        $form = $this->createForm(TaskUpdateType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_detail', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/update.html.twig', [
            'title' => 'Update task',
            'icon' => 'pencil',
            'taskForm' => $form->createView(),
        ]);
    }
}
