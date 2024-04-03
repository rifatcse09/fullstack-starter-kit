<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class HttpRequestException extends Exception
{
    protected $message = "Failed to handle the request.";
}
