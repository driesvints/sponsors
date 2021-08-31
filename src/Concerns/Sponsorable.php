<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Concerns;

use GitHub\Sponsors\Client;
use GitHub\Sponsors\Login;
use Illuminate\Support\LazyCollection;

trait Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool
    {
        return $this->gitHubLogin()->isSponsoredBy($sponsor);
    }

    public function isSponsoring(string $account): bool
    {
        return $this->gitHubLogin()->isSponsoring($account);
    }

    public function sponsors(array $select = ['login']): LazyCollection
    {
        return $this->gitHubLogin()->sponsors($select);
    }

    public function hasSponsors(): bool
    {
        return $this->gitHubLogin()->hasSponsors();
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

    protected function gitHubLogin(): Login
    {
        return $this->sponsorsClient()->login($this->gitHubUsername());
    }

    protected function sponsorsClient(): Client
    {
        return $this->hasGitHubToken() ? new Client($this->gitHubToken()) : app(Client::class);
    }
}
