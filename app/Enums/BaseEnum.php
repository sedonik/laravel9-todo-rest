<?php
declare(strict_types=1);

namespace App\Enums;

trait BaseEnum
{
    /**
     * @return string
     */
    public static function getImplodedList(): string
    {
        return implode(',', array_column(static::cases(), 'value'));
    }
}
