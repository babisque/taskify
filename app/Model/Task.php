<?php

declare(strict_types=1);

namespace App\Taskify\Model;

use DateTime;
use DateTimeImmutable;

class Task
{
    public readonly int $id;
    public readonly DateTime $createdAt;
    public readonly DateTime $updatedAt;

    public function __construct(
        public string $name,
        public string $description,
        public int $priority,
        public int $status
    )
    {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(DateTime $date): void
    {
        $this->createdAt = $date;
    }

    public function setUpdatedAt(DateTime $date): void
    {
        $this->updatedAt = $date;
    }
}