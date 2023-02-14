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
        $taskList = array_map(function (Task $task): array {
            return [
                'id' => $task->getId(),
                'name' => $task->name,
                'description' => $task->description,
                'priority' => $task->priority,
                'status' => $task->status,
                'created_at' => $task->getCreatedAt(),                
            ];
        }, $this->taskRepository->all());

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($taskList);
	}
}