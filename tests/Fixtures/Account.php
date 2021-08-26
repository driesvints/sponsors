<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use GitHub\Sponsors\Concerns\Sponsorable;
use GitHub\Sponsors\Contracts\Sponsorable as SponsorableContract;

final class Account implements SponsorableContract
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
