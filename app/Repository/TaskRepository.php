<?php

declare(strict_types=1);

namespace App\Taskify\Repository;

use App\Taskify\Model\Task;
use DateTime;
use PDO;

class TaskRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Task $task): bool
    {
        $sql = "INSERT INTO task (name, description, priority, status, created_at) VALUES (:name, :description, :priority, :status, :created_at);";

        $task->setCreatedAt(new DateTime("now"));

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $task->name);
        $statement->bindValue(':description', $task->description);
        $statement->bindValue(':priority', $task->priority);
        $statement->bindValue(':status', $task->status);
        $statement->bindValue(':created_at', $task->createdAt->format('Y-m-d H:i:s'));

        $result = $statement->execute();

        $id = $this->pdo->lastInsertId();
        $task->setId(intval($id));
        return $result;
    }

    public function remove(int $id): bool
    {
        $sql = "DELETE FROM task WHERE id = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);

        return $statement->execute();
    }

    public function update(Task $task): bool
    {
        $sql = "UPDATE task SET name = :name, description = :description, priority = :priority, status = :status, created_at = :created_at, updated_at = :updated_at;";

        $date = new DateTime("now");

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $task->name);
        $statement->bindValue(':description', $task->description);
        $statement->bindValue(':priority', $task->priority);
        $statement->bindValue(':status', $task->status);
        $statement->bindValue(':created_at', $task->createdAt);
        $statement->bindValue(':updated_at', $date);

        return $statement->execute();
    }

    /**
     * @return Task[]
     */
    public function all(): array
    {
        $taskList = $this->pdo
            ->query("SELECT * FROM task;")
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            $this->hydrateTask(...),
            $taskList
        );
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM task WHERE id = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->execute();

        return $this->hydrateTask($statement->fetch(PDO::FETCH_ASSOC));
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