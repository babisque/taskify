<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;

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
        if ($id === false || $id === null) {
            http_response_code(204);
            exit();
        }

        if ($this->taskRepository->remove($id)) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
	}
}