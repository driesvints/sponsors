<?php

declare(strict_types=1);

namespace Tests;

use GitHub\Sponsors\Client;
use GitHub\Sponsors\GitHubSponsorsServiceProvider;
use Orchestra\Testbench\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_a_github_user_is_sponsored_by_someone()
    {
        $this->assertTrue(
            $this->client()->login('driesvints')->isSponsoredBy('nunomaduro')
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

    /** @test */
    public function it_can_check_if_the_authed_account_has_sponsors()
    {
        $this->assertTrue(
            $this->client()->viewer()->hasSponsors()
        );
    }

    /** @test */
    public function it_can_retrieve_all_sponsors_for_the_authed_account()
    {
        $sponsors = $this->client()->viewer()->sponsors();

        $this->assertFalse($sponsors->isEmpty());

        foreach ($sponsors as $sponsor) {
            $this->assertArrayHasKey('login', $sponsor);
            $this->assertIsString($sponsor['login']);
        }
    }

    /** @test */
    public function it_can_check_if_a_user_has_sponsors()
    {
        $this->assertTrue(
            $this->client()->login('Gummibeer')->hasSponsors()
        );
    }

    /** @test */
    public function it_can_retrieve_all_sponsors_of_an_user()
    {
        $sponsors = $this->client()->login('Gummibeer')->sponsors();

        $this->assertFalse($sponsors->isEmpty());

        foreach ($sponsors as $sponsor) {
            $this->assertArrayHasKey('login', $sponsor);
            $this->assertIsString($sponsor['login']);
        }
    }

    /** @test */
    public function it_can_check_if_an_organization_has_sponsors()
    {
        $this->assertTrue(
            $this->client()->login('larabelles')->hasSponsors()
        );
    }

    /** @test */
    public function it_can_retrieve_all_sponsors_for_an_organization()
    {
        $sponsors = $this->client()->login('larabelles')->sponsors();

        $this->assertFalse($sponsors->isEmpty());

        foreach ($sponsors as $sponsor) {
            $this->assertArrayHasKey('login', $sponsor);
            $this->assertIsString($sponsor['login']);
        }
    }

    private function client(): Client
    {
        return $this->app->make(Client::class);
    }

    protected function getPackageProviders($app): array
    {
        return [GitHubSponsorsServiceProvider::class];
    }
}
