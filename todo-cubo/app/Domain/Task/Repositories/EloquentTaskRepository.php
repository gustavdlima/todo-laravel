<?php

namespace App\Infrastructure\Repositories;

use App\Domain\TaskManagement\Entities\Task;
use App\Domain\TaskManagement\Entities\TaskComment;
use App\Domain\TaskManagement\Repositories\TaskRepositoryInterface;
use App\Domain\TaskManagement\ValueObjects\TaskStatus;
use App\Infrastructure\Models\Task as TaskModel;
use App\Infrastructure\Models\TaskComment as TaskCommentModel;
use DateTimeInterface;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): ?Task
    {
        $taskModel = TaskModel::findOrFail($id);
        return $this->mapToEntity($taskModel);
    }

    public function findAll(int $userId): array
    {
        $taskModels = TaskModel::where('user_id', $userId)->get();
        return $this->mapCollectionToEntities($taskModels);
    }

    public function findByStatus(int $userId, TaskStatus $status): array
    {
        $taskModels = TaskModel::where('user_id', $userId)
            ->where('status', $status->toString())
            ->get();

        return $this->mapCollectionToEntities($taskModels);
    }

    public function findByDateRange(int $userId, DateTimeInterface $start, DateTimeInterface $end): array
    {
        $taskModels = TaskModel::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        return $this->mapCollectionToEntities($taskModels);
    }

	public function findByCreationDate(int $userId, DateTimeInterface $date): array
	{
		$taskModels = TaskModel::where('user_id', $userId)
        ->whereDate('created_at', $date)->get();

		return $this->mapCollectionToEntities($taskModels);
	}

    public function save(Task $task): Task
    {
        $taskModel = $task->getId()
            ? TaskModel::findOrFail($task->getId())
            : new TaskModel();

        $taskModel->title = $task->getTitle();
        $taskModel->description = $task->getDescription();
        $taskModel->status = $task->getStatus()->toString();
        $taskModel->due_date = $task->getDueDate();
        $taskModel->user_id = $task->getUserId();
        $taskModel->completed = $task->isCompleted();

        $taskModel->save();

        return $this->mapToEntity($taskModel);
    }

    public function delete(int $id): bool
    {
        return TaskModel::destroy($id) > 0;
    }

    public function addComment(int $taskId, string $content, int $userId): void
    {
        $commentModel = new TaskCommentModel();
        $commentModel->content = $content;
        $commentModel->task_id = $taskId;
        $commentModel->user_id = $userId;
        $commentModel->save();
    }

    public function getComments(int $taskId): array
    {
        $commentModels = TaskCommentModel::where('task_id', $taskId)->get();

        return $commentModels->map(function (TaskCommentModel $model) {
            return new TaskComment(
                $model->content,
                $model->user_id,
                $model->task_id,
                $model->id
            );
        })->toArray();
    }

    private function mapToEntity(TaskModel $model): Task
    {
        $task = new Task(
            $model->title,
            $model->description,
            TaskStatus::fromString($model->status),
            $model->due_date,
            $model->user_id,
            $model->id
        );

        return $task;
    }

    private function mapCollectionToEntities($taskModels): array
    {
        return $taskModels->map(function ($model) {
            return $this->mapToEntity($model);
        })->toArray();
    }
}
