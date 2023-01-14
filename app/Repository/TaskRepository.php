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
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $task->name);
        $statement->bindValue(':description', $task->description);
        $statement->bindValue(':priority', $task->priority);
        $statement->bindValue(':status', $task->status);
        $statement->bindValue(':created_at', $task->setCreatedAt(new DateTime("now")));

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

    // public function update(Task $task): bool
    // {
    //     $sql = "UPDATE task SET name = :name, description = :description, priority = :priority, status = :status, created_at = :created_at;";
    //     $statement = $this->pdo->prepare($sql);
    //     $statement->bindValue(':name', $task->name);
    //     $statement->bindValue(':description', $task->description)
    // }
}