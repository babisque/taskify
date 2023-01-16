<?php

declare(strict_types=1);

namespace App\Taskify\Controller;
use App\Taskify\Repository\TaskRepository;

interface Controller
{
    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct($taskRepository);
    public function processRequest();
}