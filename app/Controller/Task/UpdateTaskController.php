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
        if (!ValidationHelper::validateId($id)) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(["message" => "Invalid task ID"], JSON_PRETTY_PRINT);
            exit();
        }

        $task = $this->taskRepository->findById($id);
        if ($task === null) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(["message" => "Task not found"], JSON_PRETTY_PRINT);
            exit();
        }

        $priority = ValidationHelper::validatePriority($taskData['priority']);
        $taskData['priority'] = $priority;

        $status = ValidationHelper::validateStatus($taskData['status']);
        $taskData['status'] = $status;

        $taskData['name'] = trim($taskData['name']);
        $taskData['description'] = trim($taskData['description']);

        $task = new Task($taskData['name'], $taskData['description'], $taskData['priority'], $taskData['status']);
        $task->setId($id);

        if (!ValidationHelper::isValidObject($task)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(["http_status_code" => 400, "error" => "bad request"], JSON_PRETTY_PRINT);
            exit();
        }

        $this->taskRepository->update($task);
        
        http_response_code(204);
    }
}