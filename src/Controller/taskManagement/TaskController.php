<?php

namespace App\Controller\taskManagement;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(): Response
    {
        return $this->render('task/index.html.twig', [
            'title' => 'Create task',
            'icon' => 'plus-circle-dotted'
        ]);
    }
}
