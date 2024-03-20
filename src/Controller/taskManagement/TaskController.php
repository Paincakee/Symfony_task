<?php

namespace App\Controller\taskManagement;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
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
    #[Route('/tasks', name: 'app_task_view')]
    public function index(TaskRepository $taskRepository): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'title' => 'Tasks',
            'icon' => 'columns-gap',
            'tasks' => $tasks
        ]);
    }

    #[Route('/task_create/{id}', name: 'app_task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        // Get the current user
        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
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
}
