<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dries\Sponsors\Concerns\Sponsorable;

final class Account
{
    use Sponsorable;

    public string $github;

    public ?string $token;

    public bool $isOrganization;

    public function __construct(string $username, ?string $token, bool $isOrganization)
    {
        $this->github = $username;
        $this->token = $token;
        $this->isOrganization = $isOrganization;
    }

    public function isGitHubOrganization(): bool
    {
        return $this->isOrganization;
    }

    public function gitHubToken(): ?string
    {
        return $this->token;
    }
}
