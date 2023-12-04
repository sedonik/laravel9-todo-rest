<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * TaskPriorityEnum enum
 */
enum TaskPriorityEnum: int
{
    use \App\Enums\BaseEnum;

    case NEW = 1;
    case IN_PROGRESS = 2;
    case UPDATED = 3;
    case COMPLETED = 4;
    case FAILED = 5;
}
