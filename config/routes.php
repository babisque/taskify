<?php

declare(strict_types=1);

use App\Taskify\Controller\Task\{
    AddTaskController,
    OneTaskController,
    TaskListController,
    DeleteTaskController
};

return [
    'GET|/list-task' => TaskListController::class,
    'GET|/task' => OneTaskController::class,
    'POST|/new-task' => AddTaskController::class,
    'GET|/delete' => DeleteTaskController::class,
];