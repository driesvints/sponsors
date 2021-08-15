<?php

declare(strict_types=1);

namespace Dries\Sponsors\Facades;

use Dries\Sponsors\Sponsors;
use Illuminate\Support\Facades\Facade;

final class SponsorsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Sponsors::class;
    }
}
