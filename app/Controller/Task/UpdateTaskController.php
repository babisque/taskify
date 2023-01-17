<?php

declare(strict_types=1);

namespace App\Taskify\Controller\Task;

use App\Taskify\Controller\Controller;
use App\Taskify\Helper\ValidationHelper;
use App\Taskify\Model\Task;
use DateTime;

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
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            http_response_code(204);
            exit();
        }

        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $priority = filter_input(INPUT_POST, 'priority', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        $date = new DateTime(filter_input(INPUT_POST, 'created_at'));

        $task = new Task($name, $description, $priority, $status);
        $task->setId($id);
        $task->setCreatedAt($date);

        if (ValidationHelper::isValidObject($task) === false) {
            http_response_code(400);
            exit();
        }

        if ($this->taskRepository->update($task)) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
	}
}