<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Concerns;

use GitHub\Sponsors\GitHubSponsors;
use Illuminate\Http\Client\Factory;

trait Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool
    {
        return $this->sponsorsClient()->isSponsoredBy(
            $this->gitHubUsername(), $sponsor
        );
    }

    public function isSponsoring(string $account): bool
    {
        return $this->sponsorsClient()->isSponsoredBy(
            $account, $this->gitHubUsername()
        );
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

    protected function sponsorsClient(): GitHubSponsors
    {
        if (! $this->hasGitHubToken()) {
            return app(GitHubSponsors::class);
        }

        return new GitHubSponsors(new Factory(), $this->gitHubToken());
    }
}
