<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Account;

/**
 * These tests cover scenarios where the sponsorable needs to be authenticated so we can check for private sponsors.
 * Calls made as an organization require a personal access token from a user that has access to the organization.
 *
 * At the moment this test can only be run by the package's CI build.
 *
 * @group Private
 */
class PrivateSponsorsTest extends TestCase
{
    /** @test */
    public function sponsorables_can_check_if_they_are_privately_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoredBy('claudiodekker')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_privately_sponsoring_someone()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoring('claudiodekker')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_privately_sponsored_by_an_organization()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoredByOrganization('laravelio')
        );
    }

    /** @test */
    public function sponsorables_can_check_if_they_are_privately_sponsoring_an_organization()
    {
        $this->assertTrue(
            $this->sponsorable('driesvints')->isSponsoringOrganization('laravelio')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_privately_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoredBy('driesvints')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_privately_sponsoring_someone()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoring('driesvints')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_privately_sponsored_by_another_organization()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoredByOrganization('spatie')
        );
    }

    /** @test */
    public function organizations_can_check_if_they_are_privately_sponsoring_another_organization()
    {
        $this->assertTrue(
            $this->organization('laravelio')->isSponsoringOrganization('spatie')
        );
    }

    protected function sponsorable(string $username, bool $isOrganization = false): Account
    {
        return new Account($username, getenv('GH_SPONSORS_TOKEN'), $isOrganization);
    }

    protected function organization(string $username): Account
    {
        return $this->sponsorable($username, true);
    }
}
