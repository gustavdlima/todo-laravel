<?php

namespace App\Domain\TaskManagement\Services;

use App\Domain\TaskManagement\Entities\Task;
use App\Domain\TaskManagement\Repositories\TaskRepositoryInterface;
use App\Domain\TaskManagement\ValueObjects\TaskStatus;
use App\Domain\TaskManagement\ValueObjects\SortDirection;

use DateTime;

class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function createTask(
        string $title,
        string $description,
        DateTime $dueDate,
        int $userId
    ): Task {
        $task = new Task(
            $title,
            $description,
            TaskStatus::PENDING(),
            $dueDate,
            $userId
        );

        return $this->taskRepository->save($task);
    }

    public function updateTask(
        int $taskId,
        string $title,
        string $description,
        TaskStatus $status,
        DateTime $dueDate
    ): Task {
        $task = $this->taskRepository->findById($taskId);

        $task->setTitle($title)
            ->setDescription($description)
            ->setStatus($status)
            ->setDueDate($dueDate);

        return $this->taskRepository->save($task);
    }

    public function changeTaskStatus(int $taskId, TaskStatus $status): Task
    {
        $task = $this->taskRepository->findById($taskId);
        $task->setStatus($status);

        return $this->taskRepository->save($task);
    }

    public function deleteTask(int $taskId): bool
    {
        return $this->taskRepository->delete($taskId);
    }

    public function addComment(int $taskId, string $content, int $userId): void
    {
        $this->taskRepository->addComment($taskId, $content, $userId);
    }

    public function getTasksByStatus(int $userId, TaskStatus $status): array
    {
        return $this->taskRepository->findByStatus($userId, $status);
    }

    public function getTasksByDateRange(int $userId, DateTime $start, DateTime $end): array
    {
        return $this->taskRepository->findByDateRange($userId, $start, $end);
    }

	public function getTaskByCreationDate(int $userId, SortDirection $direction = null): array
	{
		return $this->taskRepository->findByCreationDate($userId, $direction);
	}

}
