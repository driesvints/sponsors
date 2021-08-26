<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Concerns;

use GitHub\Sponsors\ClientFactory;
use GitHub\Sponsors\Clients\LoginClient;
use Illuminate\Http\Client\Factory;

trait Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool
    {
        return $this->sponsorsClient()
            ->isSponsoredBy($sponsor);
    }

    public function isSponsoring(string $account): bool
    {
        return $this->sponsorsClient()
            ->isSponsoring($account);
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

    protected function sponsorsClient(): LoginClient
    {
        $factory = $this->hasGitHubToken()
            ? new ClientFactory($this->gitHubToken())
            : app(ClientFactory::class);

        return $factory->login($this->gitHubUsername());
    }
}
