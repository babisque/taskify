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
        if ($id === null || $id === false) {
            return false;
        }

        return true;
    }

    public static function validatePriority($priority): bool
    {
        if ($priority < 1 || $priority > 3) {
            return false;
        }

        return true;
    }

    public static function validateStatus($status): bool
    {
        if ($status < 1 || $status > 3) {
            return false;
        }

        return true;
    }
}