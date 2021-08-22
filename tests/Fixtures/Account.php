<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use GitHub\Sponsors\Concerns\Sponsorable;

final class Account
{
    use Sponsorable;

    public function __construct(
        public string $github,
        public ?string $token = null
    ) {
    }

    public function gitHubToken(): ?string
    {
        return $this->token;
    }
}
