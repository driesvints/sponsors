<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Facades;

use GitHub\Sponsors\GitHubSponsors;
use Illuminate\Support\Facades\Facade;

final class GitHubSponsorsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GitHubSponsors::class;
    }
}
