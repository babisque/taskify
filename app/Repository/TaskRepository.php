<?php

declare(strict_types=1);

namespace App\Taskify\Repository;

use App\Taskify\Model\Task;
use DateTime;
use InvalidArgumentException;
use PDO;
use PDOException;
use RuntimeException;

class TaskRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Task $task): bool
    {
        try {
            $sql = "INSERT INTO task (name, description, priority, status, created_at) VALUES (:name, :description, :priority, :status, now());";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':name', $task->name);
            $statement->bindValue(':description', $task->description);
            $statement->bindValue(':priority', $task->priority);
            $statement->bindValue(':status', $task->status);

            $result = $statement->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
            exit();
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

            if (!$result) {
                throw new RuntimeException("Failed to delete task with ID: $id");
            }

            return true;
        } catch (PDOException | InvalidArgumentException | RuntimeException $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
            exit();
        }
    }

    public function update(Task $task): bool
    {
        try {
            $sql = "UPDATE task SET name = :name, description = :description, priority = :priority, status = :status, updated_at = now() WHERE id = :id;";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':name', $task->name);
            $statement->bindValue(':description', $task->description);
            $statement->bindValue(':priority', $task->priority);
            $statement->bindValue(':status', $task->status);
            $statement->bindValue(':id', $task->getId());
            $result = $statement->execute();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
            exit();
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
            echo json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
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
            echo json_encode(["error" => $e->getMessage()], JSON_PRETTY_PRINT);
            exit();
        }

        if ($task === null || $task === false) {
            return;
        }
        return $this->hydrateTask($task);
    }

    private function hydrateTask(array $taskData): Task
    {
        $task = new Task($taskData['name'], $taskData['description'], $taskData['priority'], $taskData['status']);
        $task->setId($taskData['id']);
        $task->setCreatedAt($taskData['created_at']);
        if (isset($taskData['updated_at'])) {
            $task->setUpdatedAt($taskData['updated_at']);
        }

        return $task;
    }
}