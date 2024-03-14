<?php

namespace App\Controller\taskManagement;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'app_task_view')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'title' => 'Tasks',
            'icon' => 'columns-gap'
        ]);
    }

    #[Route('/task_create', name: 'app_task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the current user
        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('task/create.html.twig', [
            'title' => 'Create task',
            'icon' => 'plus-circle-dotted',
            'taskForm' => $form->createView(),
        ]);
    }
}
