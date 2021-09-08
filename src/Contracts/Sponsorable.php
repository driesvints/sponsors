<?php

declare(strict_types=1);

namespace GitHub\Sponsors\Contracts;

use Illuminate\Support\LazyCollection;

interface Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool;

    public function isSponsoring(string $account): bool;

    public function hasSponsors(): bool;

    public function sponsors(array $select = ['login']): LazyCollection;
}
