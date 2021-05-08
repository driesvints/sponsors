<?php

declare(strict_types=1);

namespace Dries\Sponsors\Facades;

use Illuminate\Support\Facades\Facade;
use Dries\Sponsors\Sponsors;

final class SponsorsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Sponsors::class;
    }
}
