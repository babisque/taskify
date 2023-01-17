<?php

declare(strict_types=1);

namespace App\Taskify\Repository;

use App\Taskify\Model\Task;
use DateTime;
use PDO;
use PDOException;

class TaskRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Task $task): bool
    {
        $task->setCreatedAt(new DateTime("now"));

        try {
            $sql = "INSERT INTO task (name, description, priority, status, created_at) VALUES (:name, :description, :priority, :status, :created_at);";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':name', $task->name);
            $statement->bindValue(':description', $task->description);
            $statement->bindValue(':priority', $task->priority);
            $statement->bindValue(':status', $task->status);
            $statement->bindValue(':created_at', $task->createdAt->format('Y-m-d H:i:s'));

            $result = $statement->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e));
            return false;
        }

        $id = $this->pdo->lastInsertId();
        $task->setId(intval($id));
        return $result;
    }

    public function remove(int $id): bool
    {
        try {
            $sql = "DELETE FROM task WHERE id = ?;";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(1, $id);
            $result = $statement->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e));
            return false;
        }

        return $result;
    }

    public function update(Task $task): bool
    {
        $date = new DateTime("now");

        try {
            $sql = "UPDATE task SET name = :name, description = :description, priority = :priority, status = :status, created_at = :created_at, updated_at = :updated_at;";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':name', $task->name);
            $statement->bindValue(':description', $task->description);
            $statement->bindValue(':priority', $task->priority);
            $statement->bindValue(':status', $task->status);
            $statement->bindValue(':created_at', $task->createdAt);
            $statement->bindValue(':updated_at', $date);
            $result = $statement->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e));
            return false;
        }

        return $result;
    }

    /**
     * @return Task[]
     */
    public function all(): array
    {
        try {
            $taskList = $this->pdo
                ->query("SELECT * FROM task;")
                ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e));
            exit();
        }

        return array_map(
            $this->hydrateTask(...),
            $taskList
        );
    }

    public function findById(int $id)
    {
        try {
            $sql = "SELECT * FROM task WHERE id = ?;";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(1, $id, PDO::PARAM_INT);
            $statement->execute();
            $task = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e));
            exit();
        }

        if ($task !== false && $task !== null) {
            return $this->hydrateTask($task);
        } else {
            return false;
        }
    }

    private function hydrateTask(array $taskData): Task
    {

        $task = new Task($taskData['name'], $taskData['description'], $taskData['priority'], $taskData['status']);
        $task->setId($taskData['id']);
        $task->setCreatedAt(new DateTime($taskData['created_at']));
        if (isset($taskData['updated_at'])) {
            $task->setUpdatedAt($taskData['updated_at']);
        }
        return $task;
    }
}