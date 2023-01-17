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
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');

        $priority = filter_input(INPUT_POST, 'priority', FILTER_VALIDATE_INT);
        if ($priority < 1 || $priority > 3) {
            $priority = 1;
        }

        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        if ($status < 1 || $status > 3) {
            $status = 1;
        }

        $task = new Task($name, $description, $priority, $status);
        if (!ValidationHelper::isValidObject($task)) {
            http_response_code(400);
            exit();
        }

        if (!$this->taskRepository->add($task)) {
            http_response_code(400);
        } else {
            http_response_code(201);
        }
	}
}