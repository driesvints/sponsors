<?php

namespace GitHub\Sponsors\Contracts;

interface Client
{
    public function isSponsoredBy(string $sponsor): bool;

    public function isSponsoring(string $account): bool;
}
