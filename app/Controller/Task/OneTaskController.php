<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;
use App\Taskify\Helper\ValidationHelper;

class OneTaskController implements Controller
{	/**
	 * @param \App\Taskify\Repository\TaskRepository $taskRepository
	 */
	public function __construct(private $taskRepository)
    {
	}

	public function processRequest()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!ValidationHelper::validateId($id)) {
            http_response_code(204);
            exit();
        }

        $taskData = $this->taskRepository->findById($id);
        $task = get_object_vars($taskData);
        $task['id'] = $taskData->getId();
        $task['created_at'] = $taskData->getCreatedAt();

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($task);
	}
}