<?php

declare(strict_types=1);

namespace App\Taskify\Model;

use DateTime;
use DateTimeImmutable;

class Task
{
    private int $id;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        public string $name,
        public string $description,
        public int $priority,
        public int $status
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $date): void
    {
        $this->createdAt = new DateTime($date);
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($date): void
    {

        $this->updatedAt = new DateTime($date);
    }
}