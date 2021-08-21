<?php

declare(strict_types=1);

namespace Dries\GitHubSponsors\Facades;

use Dries\GitHubSponsors\GitHubSponsors;
use Illuminate\Support\Facades\Facade;

final class GitHubSponsorsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GitHubSponsors::class;
    }
}
