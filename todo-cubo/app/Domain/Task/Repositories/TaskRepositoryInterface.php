<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Entities\Task;
use App\Domain\Task\ValueObjects\TaskStatus;

use DateTimeInterface;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;
    public function findAll(int $userId): array;
    public function findByStatus(int $userId, TaskStatus $status): array;
    public function findByDateRange(int $userId, DateTimeInterface $start, DateTimeInterface $end): array;
	public function findByCreationDate(int $userId, DateTimeInterface $date): array;
    public function save(Task $task): Task;
    public function delete(int $id): bool;
    public function addComment(int $taskId, string $content, int $userId): void;
    public function getComments(int $taskId): array;
}
