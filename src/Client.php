<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Clients\Login;
use GitHub\Sponsors\Clients\Viewer;
use Illuminate\Http\Client\Factory;

final class Client
{
    private GraphqlClient $graphql;

    public function __construct(string $token)
    {
        $this->graphql = new GraphqlClient($token);
    }

    public function viewer(): Viewer
    {
        return new Viewer($this->graphql);
    }

    public function login(string $login): Login
    {
        return new Login($this->graphql, $login);
    }
}
