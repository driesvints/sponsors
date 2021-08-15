<?php

declare(strict_types=1);

namespace Tests;

use Dries\Sponsors\Sponsors;
use Dries\Sponsors\SponsorsServiceProvider;
use Orchestra\Testbench\TestCase;

class SponsorsTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_a_github_user_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->isSponsoredBy('driesvints', 'nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->isOrganizationSponsoredBy('laravelio', 'nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsoring_someone()
    {
        $this->assertTrue(
            $this->client()->isSponsoredBy('driesvints', 'kontentino')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsoring_another_organization()
    {
        $this->assertTrue(
            $this->client()->isOrganizationSponsoredBy('laravelio', 'akaunting')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsoring_someone()
    {
        $this->assertTrue(
            $this->client()->isViewerSponsoring('nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsoring_an_organization()
    {
        $this->assertTrue(
            $this->client()->isViewerSponsoringOrganization('Homebrew')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->isViewerSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsored_by_an_organization()
    {
        $this->assertTrue(
            $this->client()->isViewerSponsoredByOrganization('kontentino')
        );
    }

    private function client(): Sponsors
    {
        return $this->app->make(Sponsors::class);
    }

    protected function getPackageProviders($app)
    {
        return [SponsorsServiceProvider::class];
    }
}
