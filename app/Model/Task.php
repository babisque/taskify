<?php

declare(strict_types=1);

namespace App\Taskify\Model;

use DateTime;
use DateTimeImmutable;

class Task
{
    private readonly int $id;
    public readonly DateTime $createdAt;

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
}