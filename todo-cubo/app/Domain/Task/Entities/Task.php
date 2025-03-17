<?php

namespace App\Domain\TaskManagement\Entities;

use App\Domain\TaskManagement\ValueObjects\TaskStatus;
use DateTimeInterface;
use App\Domain\TaskManagement\Events\TaskCompletedEvent;
use App\Domain\UserManagement\Entities\User;

class Task
{
    private int $id;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private DateTimeInterface $dueDate;
    private int $userId;
    private array $comments = [];

    public function __construct(
        string $title,
        string $description,
        TaskStatus $status,
        DateTimeInterface $dueDate,
        int $userId,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->dueDate = $dueDate;
        $this->userId = $userId;
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

    public function addComment(string $content, int $userId): void
    {
        $comment = new TaskComment($content, $userId, $this->id);
        $this->comments[] = $comment;
    }

    public function getComments(): array
    {
        return $this->comments;
    }
}

