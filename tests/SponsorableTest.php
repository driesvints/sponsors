<?php

declare(strict_types=1);

namespace Tests;

use Dries\GitHubSponsors\GitHubSponsorsServiceProvider;
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
            $this->sponsorable('driesvints')->isSponsoredByOrganization('kontentino')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_sponsoring_an_organization()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoringOrganization('Homebrew')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsoring_someone()
    {
        $this->assertTrue(
            $this->organization('kontentino')->isSponsoring('driesvints')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsored_by_another_organization()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoredByOrganization('akaunting')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_sponsoring_another_organization()
    {
        $this->assertTrue(
            $this->organization('akaunting')->isSponsoringOrganization('laravelio')
        );
    }

    protected function sponsorable(string $username, bool $isOrganization = false): Account
    {
        return new Account($username, null, $isOrganization);
    }

    protected function organization(string $username): Account
    {
        return $this->sponsorable($username, true);
    }

    protected function getPackageProviders($app): array
    {
        return [GitHubSponsorsServiceProvider::class];
    }
}
