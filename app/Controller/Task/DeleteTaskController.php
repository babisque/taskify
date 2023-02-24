<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;
use App\Taskify\Helper\ValidationHelper;

class DeleteTaskController implements Controller
{

    /**
     * @param \App\Taskify\Repository\TaskRepository $taskRepository
     */
    public function __construct(private $taskRepository)
    {
    }

    public function processRequest()
    {
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

        $this->taskRepository->remove($id);

        http_response_code(204);
        header('Content-Type: application/json');
        echo json_encode(["Message" => "Task deleted successfully"], JSON_PRETTY_PRINT);
    }
}