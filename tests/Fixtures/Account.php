<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Dries\Sponsors\Concerns\Sponsorable;

final class Account
{
    use Sponsorable;

    public function __construct(
        public string $github,
        public ?string $token,
        public bool $isOrganization
    ) {}

    public function isGitHubOrganization(): bool
    {
        return $this->isOrganization;
    }

    public function gitHubToken(): ?string
    {
        return $this->token;
    }
}
