<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use GitHub\Sponsors\Concerns\Sponsorable;

final class Account
{
    use Sponsorable;

    public string $github;

    public ?string $token = null;

    public function __construct(string $github, ?string $token = null)
    {
        $this->github = $github;
        $this->token = $token;
    }

    public function gitHubToken(): ?string
    {
        return $this->token;
    }
}
