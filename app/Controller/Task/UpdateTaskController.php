<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;
use App\Taskify\Helper\ValidationHelper;
use App\Taskify\Model\Task;

class UpdateTaskController implements Controller
{

    /**
     * @param \App\Taskify\Repository\TaskRepository $taskRepository
     */
    public function __construct(private $taskRepository)
    {
    }

    public function processRequest()
    {
        $request = file_get_contents('php://input');
        $taskData = json_decode($request, true);
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!ValidationHelper::validatePriority($taskData['priority'])) {
            $taskData['priority'] = 1;
        }

        if (!ValidationHelper::validateStatus($taskData['status'])) {
            $taskData['status'] = 1;
        }

        $taskData['name'] = trim($taskData['name']);
        $taskData['description'] = trim($taskData['description']);

        $task = new Task($taskData['name'], $taskData['description'], $taskData['priority'], $taskData['status']);
        $task->setId($id);

        if (!ValidationHelper::isValidObject($task)) {
            http_response_code(400);
            exit();
        }

        if (!$this->taskRepository->update($task)) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
    }
}