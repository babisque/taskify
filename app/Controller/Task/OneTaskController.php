<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;

class OneTaskController implements Controller
{	/**
	 * @param \App\Taskify\Repository\TaskRepository $taskRepository
	 * @return mixed
	 */
	public function __construct(private $taskRepository)
    {
	}
	
	/**
	 * @return mixed
	 */
	public function processRequest()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $task = $this->taskRepository->findById($id);
        $jsonData = json_encode($task);
        header('Content-Type: application/json');
        http_response_code(200);
        echo $jsonData;
	}
}