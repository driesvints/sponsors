<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Exceptions;

use Exception;

final class BadCredentialsException extends Exception
{
    public static function badToken(): self
    {
        return new self('A bad token was provided for the GraphQL API request to GitHub.');
    }
}
