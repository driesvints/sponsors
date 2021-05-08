<?php

declare(strict_types=1);

namespace Dries\Sponsors;

use Github\Client as GitHub;
use Illuminate\Support\ServiceProvider;

final class SponsorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/github-sponsors.php', 'github-sponsors');

        $this->app->singleton(Sponsors::class, function ($app) {
            $config = $app['config']->get('github-sponsors');

            $client = new GitHub();

            $client->authenticate($config['token'], null, GitHub::AUTH_ACCESS_TOKEN);

            return new Sponsors($client);
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
