<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Concerns;

use GitHub\Sponsors\Client;
use GitHub\Sponsors\Login;

trait Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool
    {
        return $this->sponsorsClient()->isSponsoredBy($sponsor);
    }

    public function isSponsoring(string $account): bool
    {
        return $this->sponsorsClient()->isSponsoring($account);
    }

    public function gitHubUsername(): string
    {
        return $this->github;
    }

    public function gitHubToken(): ?string
    {
        return $this->github_token ?? null;
    }

    public function hasGitHubToken(): bool
    {
        return $this->gitHubToken() !== null;
    }

    protected function sponsorsClient(): Login
    {
        $factory = $this->hasGitHubToken() ? new Client($this->gitHubToken()) : app(Client::class);

        return $factory->login($this->gitHubUsername());
    }
}
