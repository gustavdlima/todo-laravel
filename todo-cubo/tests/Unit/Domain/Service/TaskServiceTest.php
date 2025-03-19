<?php

namespace Tests\Unit\Domain\Services;

use App\Domain\Task\Entities\Task;
use App\Domain\Task\Services\TaskService;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\ValueObjects\TaskStatus;
use DateTime;
use DateTimeInterface;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
	private TaskRepositoryInterface $taskRepository;
	private TaskService $taskService;

	protected function setUp(): void
	{
		parent::setUp();
		$this->taskRepository = Mockery::mock(TaskRepositoryInterface::class);
		$this->taskService = new TaskService($this->taskRepository);
	}

	/**
	 * Test the creation of a task through the service
	 */
	public function test_can_create_task()
	{
		$taskData = [
			'title' => 'Implement API',
			'description' => 'Develop endpoints',
			'status' => TaskStatus::PENDING(),
			'userId' => 1,
			'dueDate' => new DateTime('2025-03-31'),
		];

		$mockedTask = new Task(
			title: $taskData['title'],
			description: $taskData['description'],
			status: $taskData['status'],
			userId: $taskData['userId'],
			dueDate: $taskData['dueDate']
		);

		$this->taskRepository
			->shouldReceive('save')
			->once()
			->with(Mockery::type(Task::class))
			->andReturn($mockedTask);

		$createdTask = $this->taskService->createTask(
			$taskData['title'],
			$taskData['description'],
			$taskData['dueDate'],
			$taskData['userId'],
		);

		$this->assertInstanceOf(Task::class, $createdTask);
		$this->assertEquals('Implement API', $createdTask->getTitle());
		$this->assertEquals('Develop endpoints', $createdTask->getDescription());
		$this->assertTrue($createdTask->getStatus()->equals(TaskStatus::PENDING()));
		$this->assertEquals(1, $createdTask->getUserId());
		$this->assertEquals(new DateTime('2025-03-31'), $createdTask->getDueDate());
	}

	/**
	 * Test filtering tasks by status
	 */
	public function test_can_filter_tasks_by_status()
	{
		$tasks = [
			new Task(
				title: 'Implement API',
				description: 'Develop endpoints',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2025-03-31'),
			),
			new Task(
				title: 'Implement Database',
				description: 'Develop database structure',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2025-03-31'),
			),
			new Task(
				title: 'Implement Frontend',
				description: 'Develop frontend components',
				status: TaskStatus::IN_PROGRESS(),
				userId: 1,
				dueDate: new DateTime('2025-03-31'),
			),
		];

		$this->taskRepository
			->shouldReceive('findByStatus')
			->once()
			->with(1, Mockery::type(TaskStatus::class))
			->andReturn([$tasks[0], $tasks[1]]);

		$filteredTasks = $this->taskService->getTasksByStatus(1, TaskStatus::PENDING());

		$this->assertCount(2, $filteredTasks);
		$this->assertEquals('Implement API', $filteredTasks[0]->getTitle());
		$this->assertEquals('Develop endpoints', $filteredTasks[0]->getDescription());
		$this->assertEquals('Implement Database', $filteredTasks[1]->getTitle());
		$this->assertEquals('Develop database structure', $filteredTasks[1]->getDescription());
	}

	/**
	 * Test filtering tasks by creation date
	 */
	public function test_save_task_with_created_at()
	{
		$tasks = [
			new Task(
				title: 'Implement API',
				description: 'Develop endpoints',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2025-12-02')
			),

			new Task(
				title: 'Implement Database',
				description: 'Develop database structure',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2025-12-31')
			),

			new Task(
				title: 'Implement Frontend',
				description: 'Develop frontend components',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2025-12-31')
			)
		];

		$mockedTasks = [
			new Task(
				id: 1,
				title: 'Implement API',
				description: 'Develop endpoints',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2023-12-31'),
				createdAt: new DateTime('2025-03-20')
			),

			new Task(
				id: 2,
				title: 'Implement Database',
				description: 'Develop database structure',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2023-12-31'),
				createdAt: new DateTime('2025-03-20')
			),

			new Task(
				id: 3,
				title: 'Implement Frontend',
				description: 'Develop frontend components',
				status: TaskStatus::PENDING(),
				userId: 1,
				dueDate: new DateTime('2023-12-31'),
				createdAt: new DateTime('2025-03-19')
			)
		];

		$this->taskRepository
			->shouldReceive('save')
			->times(3)
			->andReturnUsing(function ($tasks) use (&$mockedTasks) {
				foreach ($mockedTasks as $mockedTask) {
					if ($mockedTask->getTitle() === $tasks->getTitle()) {
						return $mockedTask;
					}
				}
			});

		$savedTasks = [];
		foreach ($tasks as $task) {
			$savedTasks[] = $this->taskService->createTask(
				$task->getTitle(),
				$task->getDescription(),
				$task->getDueDate(),
				$task->getUserId()
			);
		}

		$this->assertCount(3, $savedTasks);

		$createdAt0 = $savedTasks[0]->getCreatedAt()->format('Y-m-d H:i:s');
		$createdAt1 = $savedTasks[1]->getCreatedAt()->format('Y-m-d H:i:s');
		$createdAt2 = $savedTasks[2]->getCreatedAt()->format('Y-m-d H:i:s');

		$this->assertEquals($createdAt0, $createdAt1);
		$this->assertNotEquals($createdAt0, $createdAt2);
	}
}
