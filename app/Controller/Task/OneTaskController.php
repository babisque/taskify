<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;

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
        if ($id === false || $id === null) {
            http_response_code(400);
            exit();
        }

        $task = $this->taskRepository->findById($id);
        if ($task === false || $task === null) {
            http_response_code(404);
            exit();
        }

        $jsonData = json_encode($task);
        header('Content-Type: application/json');
        http_response_code(200);
        echo $jsonData;
	}
}