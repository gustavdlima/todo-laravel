<?php

namespace App\Domain\Common\ValueObjects;

class SortDirection
{
    private string $value;

    private const ASC = 'asc';
    private const DESC = 'desc';

    private function __construct(string $direction)
    {
        $direction = strtolower($direction);
        if (!in_array($direction, [self::ASC, self::DESC])) {
            throw new \InvalidArgumentException("Invalid sort direction: {$direction}");
        }

        $this->value = $direction;
    }

    public static function ASC(): self
    {
        return new self(self::ASC);
    }

    public static function DESC(): self
    {
        return new self(self::DESC);
    }

    public static function fromString(string $direction): self
    {
        return new self($direction);
    }

    public function isAscending(): bool
    {
        return $this->value === self::ASC;
    }

    public function isDescending(): bool
    {
        return $this->value === self::DESC;
    }

    public function toString(): string
    {
        return $this->value;
    }

}
