<?php

declare(strict_types=1);

namespace Dries\GitHubSponsors\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

final class BadCredentialsException extends Exception
{
    public static function fromHttpResponse(Response $response): self
    {
        return new self('A bad token was provided for the GraphQL API request to GitHub.');
    }
}
