<?php

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

class TaskStatus
{
    private string $value;

    private const PENDING = 'pending';
    private const IN_PROGRESS = 'in_progress';
    private const COMPLETED = 'completed';

    private const ALLOWED_STATUSES = [
        self::PENDING,
        self::IN_PROGRESS,
        self::COMPLETED
    ];

    private function __construct(string $value)
    {
        if (!in_array($value, self::ALLOWED_STATUSES)) {
            throw new InvalidArgumentException("Invalid task status: {$value}");
        }

        $this->value = $value;
    }

    public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    public static function IN_PROGRESS(): self
    {
        return new self(self::IN_PROGRESS);
    }

    public static function COMPLETED(): self
    {
        return new self(self::COMPLETED);
    }

    public function equals(TaskStatus $status): bool
    {
        return $this->value === $status->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, self::ALLOWED_STATUSES)) {
            throw new InvalidArgumentException("Invalid task status: {$value}");
        }

        return new self($value);
    }
}
