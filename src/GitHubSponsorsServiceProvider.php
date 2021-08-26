<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use Illuminate\Support\ServiceProvider;

final class GitHubSponsorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/github-sponsors.php', 'github-sponsors');

        $this->app->singleton(Client::class, function () {
            return new Client(config('github-sponsors.token'));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/github-sponsors.php' => config_path('github-sponsors.php'),
            ], 'github-sponsors-config');
        }
    }
}
