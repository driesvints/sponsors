<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Clients\LoginClient;
use GitHub\Sponsors\Clients\ViewerClient;
use Illuminate\Http\Client\Factory;

final class ClientFactory
{
    private GraphqlClient $graphql;

    public function __construct(string $token)
    {
        $this->graphql = new GraphqlClient(new Factory(), $token);
    }

    public function viewer(): ViewerClient
    {
        return new ViewerClient($this->graphql);
    }

    public function login(string $login): LoginClient
    {
        return new LoginClient($this->graphql, $login);
    }
}
