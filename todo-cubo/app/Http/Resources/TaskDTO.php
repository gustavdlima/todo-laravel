<?php

namespace App\Http\Resources;

use App\Domain\Task\Entities\Task;
use App\Domain\Task\ValueObjects\TaskStatus;

class TaskDTO
{
    public int $id;
    public string $title;
    public string $description;
    public string $status;
    public string $dueDate;
    public int $userId;

    public function __construct(
        int $id,
        string $title,
        string $description,
        string $status,
        string $dueDate,
        int $userId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->dueDate = $dueDate;
        $this->userId = $userId;
    }

    public static function fromEntity(Task $task): self
    {
        return new self(
            $task->getId(),
            $task->getTitle(),
            $task->getDescription(),
            $task->getStatus()->toString(),
            $task->getDueDate()->format('d/m/Y'),
            $task->getUserId()
        );
    }
}
