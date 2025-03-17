<?php

namespace App\Domain\Task\Entities;

use DateTimeInterface;

class TaskComment
{
    private ?int $id;
    private string $content;
    private int $userId;
    private int $taskId;
    private DateTimeInterface $createdAt;

    public function __construct(
        string $content,
        int $userId,
        int $taskId,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->userId = $userId;
        $this->taskId = $taskId;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
