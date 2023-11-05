<?php

namespace App\Enums;

/**
 * TaskPriorityEnum enum
 */
class TaskPriorityEnum extends BaseEnum
{
    /**
     * const NEW
     */
    public const NEW = 1;

    /**
     * const IN_PROGRESS
     */
    public const IN_PROGRESS = 2;

    /**
     * const UPDATED
     */
    public const UPDATED = 3;

    /**
     * const COMPLETED
     */
    public const COMPLETED = 4;

    /**
     * const FAILED
     */
    public const FAILED = 5;

    /**
     * @return string[]
     */
    public static function map(): array
    {
        return [
            static::NEW => 'New',
            static::IN_PROGRESS => 'In progress',
            static::UPDATED => 'Updated',
            static::COMPLETED => 'Completed',
            static::FAILED => 'Failed'
        ];
    }
}
