<?php

declare(strict_types=1);

namespace App\Taskify\Helper;

use App\Taskify\Model\Task;

class ValidationHelper
{
    public static function isValidObject($object): bool
    {
        if (is_object($object) && !empty((array) $object)) {
            return true;
        }
        return false;
    }

    public static function validateId($id): bool
    {
        return is_int($id) && $id > 0;
    }

    public static function validatePriority($priority): int
    {
        return max(1, min($priority, 3));
    }

    public static function validateStatus($status): int
    {
        return max(1, min($status, 3));
    }
}