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
}