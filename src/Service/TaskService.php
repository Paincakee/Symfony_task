<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;


class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function getTaskById($id): Task
    {
        return $this->taskRepository->find(['id' => $id]);
    }
}
