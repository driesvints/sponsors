<?php

declare(strict_types=1);

namespace Tests;

use GitHub\Sponsors\ClientFactory;
use GitHub\Sponsors\GitHubSponsorsServiceProvider;
use Orchestra\Testbench\TestCase;

class GitHubSponsorsTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_a_github_user_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->login('driesvints')->isSponsoredBy( 'nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->login('laravelio')->isSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsoring_someone()
    {
        $this->assertTrue(
            $this->client()->login('driesvints')->isSponsoredBy('kontentino')
        );
    }

    /** @test */
    public function it_can_determine_if_a_github_organization_is_sponsoring_another_organization()
    {
        $this->assertTrue(
            $this->client()->login('laravelio')->isSponsoredBy('akaunting')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsoring_someone()
    {
        $this->assertTrue(
            $this->client()->viewer()->isSponsoring('nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsoring_an_organization()
    {
        $this->assertTrue(
            $this->client()->viewer()->isSponsoring('Homebrew')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->viewer()->isSponsoredBy('nunomaduro')
        );
    }

    /** @test */
    public function it_can_determine_if_the_authed_account_is_sponsored_by_an_organization()
    {
        $this->assertTrue(
            $this->client()->viewer()->isSponsoredBy('kontentino')
        );
    }

    private function client(): ClientFactory
    {
        return $this->app->make(ClientFactory::class);
    }

    protected function getPackageProviders($app): array
    {
        return [GitHubSponsorsServiceProvider::class];
    }
}
