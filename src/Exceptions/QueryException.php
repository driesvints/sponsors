<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Exceptions;

use Exception;

final class QueryException extends Exception
{
    public static function badQuery(): self
    {
        return new self('A query exception occurred while making the GraphQL API call.');
    }
}
