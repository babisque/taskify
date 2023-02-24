<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;
use App\Taskify\Helper\ValidationHelper;
use App\Taskify\Model\Task;
use App\Taskify\Repository\TaskRepository;

class AddTaskController implements Controller
{
    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(private $taskRepository)
    {
    }

    public function processRequest()
    {
        $request = file_get_contents('php://input');
        $taskData = json_decode($request, true);

        $priority = ValidationHelper::validatePriority($taskData['priority']);
        $taskData['priority'] = $priority;

        $status = ValidationHelper::validateStatus($taskData['status']);
        $taskData['status'] = $status;

        $taskData['name'] = trim($taskData['name']);
        $taskData['description'] = trim($taskData['description']);

        $task = new Task($taskData['name'], $taskData['description'], $taskData['priority'], $taskData['status']);
        if (!ValidationHelper::isValidObject($task)) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["http_status_code" => 400, "error" => "bad request"], JSON_PRETTY_PRINT);
            exit();
        }

        $this->taskRepository->add($task);

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($task, JSON_PRETTY_PRINT);
    }
}