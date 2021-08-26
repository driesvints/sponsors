<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Exceptions\BadCredentialsException;
use GitHub\Sponsors\Exceptions\QueryException;
use Illuminate\Http\Client\Factory;

final class GraphqlClient
{
    private Factory $http;

    private string $token;

    public function __construct(string $token, ?Factory $http = null)
    {
        $this->http = $http ?? new Factory();
        $this->token = $token;
    }

    public function send(string $query, array $variables = []): array
    {
        $response = $this->http
            ->withToken($this->token)
            ->asJson()
            ->accept('application/vnd.github.v4+json')
            ->withUserAgent('github-php/sponsors')
            ->post('https://api.github.com/graphql', [
                'query' => $query,
                'variables' => $variables,
            ]);

        if ($response->status() === 401) {
            throw BadCredentialsException::badToken();
        }

        if ($response->clientError()) {
            throw QueryException::badQuery();
        }

        return $response->json('data');
    }
}
