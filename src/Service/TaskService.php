<?php

namespace App\Service;

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
}
