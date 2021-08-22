<?php

declare(strict_types=1);

namespace Tests;

use GitHub\Sponsors\GitHubSponsorsServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\Fixtures\Account;

class SponsorableTest extends TestCase
{
    /** @test */
    public function sponsorables_can_check_if_they_are_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_sponsoring_someone()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoring('nunomaduro')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_sponsored_by_an_organization()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoredBy('kontentino')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_sponsoring_an_organization()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoring('Homebrew')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->sponsorable('laravelio')->isSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsoring_someone()
    {
        $this->assertTrue(
            $this->sponsorable('kontentino')->isSponsoring('driesvints')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsored_by_another_organization()
    {
        $this->assertTrue(
            $this->sponsorable('laravelio')->isSponsoredBy('akaunting')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsoring_another_organization()
    {
        $this->assertTrue(
            $this->sponsorable('akaunting')->isSponsoring('laravelio')
        );
    }

    protected function sponsorable(string $username): Account
    {
        return new Account($username);
    }

    protected function getPackageProviders($app): array
    {
        return [GitHubSponsorsServiceProvider::class];
    }
}
