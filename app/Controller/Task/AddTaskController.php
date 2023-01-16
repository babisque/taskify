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
            echo $priority;
            $priority = 1;
        }

        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        if ($status < 1 || $status > 3) {
            echo $status;
            $status = 1;
        }

        $task = new Task($name, $description, $priority, $status);
        if (ValidationHelper::isValidObject($task) === false) {
            echo "isnt an object";
            exit();
        }

        if ($this->taskRepository->add($task) === false) {
            echo "cannot persist this";
            exit();
        } else {
            return http_response_code(201);
        }
	}
}