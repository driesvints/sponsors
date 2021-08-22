<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

final class QueryException extends Exception
{
    public static function fromHttpResponse(Response $response): self
    {
        return new self('A query exception occurred while making the GraphQL API call.');
    }
}
