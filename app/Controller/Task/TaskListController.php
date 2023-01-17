<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;
use App\Taskify\Controller\Controller;
use App\Taskify\Model\Task;

class TaskListController implements Controller
{
    
	/**
	 * @param \App\Taskify\Repository\TaskRepository $taskRepository
	 */
	public function __construct(private $taskRepository)
    {
	}
	
	public function processRequest()
    {
        $taskData = $this->taskRepository->all();
        $jsonData = json_encode($taskData);
        header('Content-Type: application/json');
        http_response_code(200);
        echo $jsonData;
	}
}