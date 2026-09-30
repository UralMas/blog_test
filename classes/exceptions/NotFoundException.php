<?php

declare(strict_types=1);

namespace App\exceptions;

use Exception;

/**
 * Исключение, когда не найдена страница
 */
class NotFoundException extends Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
