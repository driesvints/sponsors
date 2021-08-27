<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Facades;

use GitHub\Sponsors\Client;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GitHub\Sponsors\Viewer viewer()
 * @method static \GitHub\Sponsors\Login login(string $login)
 *
 * @see \GitHub\Sponsors\Client
 */
final class GitHubSponsors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
