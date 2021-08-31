<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Exceptions\BadCredentialsException;
use GitHub\Sponsors\Exceptions\QueryException;
use Illuminate\Http\Client\Factory;

final class Client
{
    private Factory $http;

    private string $token;

    public function __construct(string $token, Factory $http = null)
    {
        $this->http = $http ?? new Factory();
        $this->token = $token;
    }

    public function viewer(): Viewer
    {
        return new Viewer($this);
    }

    public function login(string $login): Login
    {
        return new Login($this, $login);
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

        if (is_array($response->json('errors')) && empty($response->json('data'))) {
            throw new QueryException($response->json('errors.0.message'));
        }

        return $response->json('data');
    }
}
