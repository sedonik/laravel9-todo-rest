<?php

namespace App\Enums;

/**
 * TaskPriorityEnum enum
 */
enum TaskPriorityEnum: int
{
    case NEW = 1;
    case IN_PROGRESS = 2;
    case UPDATED = 3;
    case COMPLETED = 4;
    case FAILED = 5;
}
