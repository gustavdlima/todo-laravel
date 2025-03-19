<?php

namespace Tests\Unit\Infrastructure\Repositories;

use App\Domain\Task\Entities\Task;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Infrastructure\Models\User;
use App\Infrastructure\Models\Task as TaskModel;
use Tests\TestCase;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentTaskRepository $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new EloquentTaskRepository();
    }

    /**
     * Testa se é possível criar uma tarefa no repositório
     */
    public function test_can_create_task()
    {
		$user = User::factory()->create();

        $task = new Task(
            title: 'New feature task',
            description: 'Try to implement new feature',
            status: TaskStatus::PENDING(),
            userId: $user->id,
            dueDate: new DateTime('2025-03-31'),
        );

        $createdTask = $this->taskRepository->save($task);

        $this->assertInstanceOf(Task::class, $createdTask);
        $this->assertNotNull($createdTask->getId());
        $this->assertEquals('New feature task', $createdTask->getTitle());
        $this->assertEquals('Try to implement new feature', $createdTask->getDescription());
        $this->assertEquals(1, $createdTask->getUserId());
        $this->assertEquals(TaskStatus::PENDING(), $createdTask->getStatus());

        $this->assertDatabaseHas('tasks', [
            'id' => $createdTask->getId(),
            'title' => 'New feature task',
            'description' => 'Try to implement new feature',
            'user_id' => 1,
            'status' => TaskStatus::PENDING()->toString()
        ]);
    }
	
	/**
     * Test whether you can filter tasks by status
     */
    public function test_can_filter_tasks_by_status()
    {
		$user = User::factory()->create();

        TaskModel::factory()->create(['status' => TaskStatus::PENDING()->toString(), 'user_id' => $user->id]);
        TaskModel::factory()->create(['status' => TaskStatus::PENDING()->toString(), 'user_id' => $user->id]);
        TaskModel::factory()->create(['status' => TaskStatus::COMPLETED()->toString(), 'user_id' => $user->id]);

        $pendingTasks = $this->taskRepository->findByStatus($user->id, TaskStatus::PENDING());
        $this->assertCount(2, $pendingTasks);
        foreach ($pendingTasks as $task) {
            $this->assertEquals(TaskStatus::PENDING()->toString(), $task->getStatus()->toString());
        }

        $inProgressTasks = $this->taskRepository->findByStatus($user->id, TaskStatus::COMPLETED());
        $this->assertCount(1, $inProgressTasks);
        foreach ($inProgressTasks as $task) {
            $this->assertEquals(TaskStatus::COMPLETED()->toString(), $task->getStatus()->toString());
        }
    }

	/**
	 * Test whether you can filter tasks by creation date
	 */
	public function test_can_filter_tasks_by_creation_date()
	{
		$user = User::factory()->create();

		TaskModel::factory()->create(['created_at' => new DateTime('2025-03-01'), 'user_id' => $user->id]);
		TaskModel::factory()->create(['created_at' => new DateTime('2025-03-02'), 'user_id' => $user->id]);
		TaskModel::factory()->create(['created_at' => new DateTime('2025-03-02'), 'user_id' => $user->id]);

		$tasks = $this->taskRepository->findByCreationDate($user->id, new DateTime('2025-03-02'));
		$this->assertCount(2, $tasks);
		foreach ($tasks as $task) {
			$this->assertGreaterThanOrEqual(new DateTime('2025-03-02'), $task->getCreatedAt());
		}
	}

}
