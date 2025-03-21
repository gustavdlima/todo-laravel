<?php

namespace App\Domain\Task\Entities;

use App\Domain\Task\ValueObjects\TaskStatus;
use DateTimeInterface;

class Task
{
    private ?int $id;
    private string $title;
    private string $description;
    private ?TaskStatus $status;
    private DateTimeInterface $dueDate;
    private int $userId;
    private array $comments = [];
    private ?DateTimeInterface $createdAt;

    public function __construct(
        string $title,
        string $description,
        ?TaskStatus $status,
        DateTimeInterface $dueDate,
        int $userId,
        ?int $id = null,
        ?DateTimeInterface $createdAt = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->dueDate = $dueDate;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDueDate(): DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isCompleted(): bool
    {
        return $this->status->equals(TaskStatus::COMPLETED());
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->dueDate < new \DateTime();
    }

    public function addComment(string $content, int $userId, int $taskId): void
    {
        $comment = new TaskComment($content, $userId, $taskId);
        $this->comments[] = $comment;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}

