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
     * Tests the creation of a task with valid values
     */
    public function test_can_create_task_with_valid_values()
    {
        $task = new Task(
            id: 1,
            title: 'Implement API',
            description: 'Develop endpoints',
            status: TaskStatus::PENDING(),
            userId: 1,
            dueDate: new DateTime('2023-12-31'),
        );

        $this->assertEquals(1, $task->getId());
        $this->assertEquals('Implement API', $task->getTitle());
        $this->assertEquals('Develop endpoints', $task->getDescription());
        $this->assertEquals(TaskStatus::PENDING(), $task->getStatus());
        $this->assertEquals(1, $task->getUserId());
        $this->assertInstanceOf(DateTimeInterface::class, $task->getDueDate());
    }

     /**
     * Test that adding a comment works correctly
     */
    public function test_can_add_comment()
    {
        $task = new Task(
            id: 1,
            title: 'Implement API',
            description: 'Develop endpoints',
            status: TaskStatus::PENDING(),
            userId: 1,
            dueDate: new DateTime('2023-12-31'),
        );

        $this->assertEquals(1, $task->getId());
        $task->addComment('Test login endpoint', 1, $task->getId());
        $comments = $task->getComments();
        $this->assertCount(1, $comments);
        $this->assertEquals('Test login endpoint', $comments[0]->getContent());
        $this->assertEquals(1, $comments[0]->getUserId());
    }

     /**
     * Test the task status change
     */
    public function test_can_change_task_status()
    {
        $task = new Task(
            id: 1,
            title: 'Implement API',
            description: 'Develop endpoints',
            status: TaskStatus::PENDING(),
            userId: 1,
            dueDate: new DateTime('2023-12-31'),
        );

        $task->setStatus(TaskStatus::IN_PROGRESS());
        $this->assertEquals(TaskStatus::IN_PROGRESS(), $task->getStatus());

        $task->setStatus(TaskStatus::COMPLETED());
        $this->assertEquals(TaskStatus::COMPLETED(), $task->getStatus());
    }


}
