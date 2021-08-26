<?php

namespace GitHub\Sponsors\Contracts;

interface Sponsorable
{
    public function isSponsoredBy(string $sponsor): bool;

    public function isSponsoring(string $account): bool;
}
