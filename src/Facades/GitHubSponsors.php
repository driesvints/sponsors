<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Facades;

use GitHub\Sponsors\ClientFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GitHub\Sponsors\Clients\ViewerClient viewer()
 * @method static \GitHub\Sponsors\Clients\LoginClient login(string $login)
 *
 * @see \GitHub\Sponsors\ClientFactory
 */
final class GitHubSponsors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ClientFactory::class;
    }
}
