<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Task\Entities\Task;
use App\Domain\Task\ValueObjects\TaskStatus;
use DateTimeInterface;
use DateTime;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class TaskTest extends TestCase
{
    /**
     * Testa a criação de uma tarefa com valores válidos
     */
    public function test_can_create_task_with_valid_values()
    {
        $task = new Task(
            title: 'Implement API',
            description: 'Develop endpoints',
            status: TaskStatus::PENDING(),
            userId: 1,
            dueDate: new DateTime('2023-12-31'),
        );

        $this->assertEquals('Implement API', $task->getTitle());
        $this->assertEquals('Develop endpoints', $task->getDescription());
        $this->assertEquals(TaskStatus::PENDING(), $task->getStatus());
        $this->assertEquals(1, $task->getUserId());
        $this->assertInstanceOf(DateTimeInterface::class, $task->getDueDate());
    }

}
