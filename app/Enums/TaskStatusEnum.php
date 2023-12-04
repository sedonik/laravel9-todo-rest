<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * TaskStatusEnum enum
 */
enum TaskStatusEnum: string
{
    use \App\Enums\BaseEnum;

    case TODO = 'todo';
    case DONE = 'done';
}
