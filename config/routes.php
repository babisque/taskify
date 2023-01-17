<?php

declare(strict_types=1);

use App\Taskify\Controller\Task\{
    AddTaskController,
    OneTaskController,
    TaskListController,
    DeleteTaskController,
    UpdateTaskController
};

return [
    'GET|/list-task' => TaskListController::class,
    'GET|/task' => OneTaskController::class,
    'GET|/delete' => DeleteTaskController::class,
    'POST|/new-task' => AddTaskController::class,
    'POST|/edit-task' => UpdateTaskController::class,
];