# PHP GitHub Sponsors

<a href="https://github.com/github-php/sponsors/actions?query=workflow%3ATests">
    <img src="https://github.com/github-php/sponsors/workflows/Tests/badge.svg" alt="Tests">
</a>
<a href="https://github.styleci.io/repos/371488434">
    <img src="https://github.styleci.io/repos/371488434/shield?style=flat" alt="Code Style">
</a>
<a href="https://packagist.org/packages/github-php/sponsors">
    <img src="https://img.shields.io/packagist/v/github-php/sponsors" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/github-php/sponsors">
    <img src="https://img.shields.io/packagist/github-php/sponsors" alt="Total Downloads">
</a>

PHP GitHub Sponsors is a package that integrates directly with [the GitHub Sponsors GraphQL API](https://docs.github.com/en/sponsors/integrating-with-github-sponsors/getting-started-with-the-sponsors-graphql-api). Using it, you can easily check if a GitHub account is sponsoring another account. This helps you implement powerful ACL capibilities in your application and the ability to grant users access to specific resources when they sponsor you.

The library is PHP agnostic but provides deep integration with [Laravel](https://laravel.com).

Here's an example how you'd use it:

```php
use GitHub\Sponsors\Client;

$client = new Client(getenv('GH_SPONSORS_TOKEN'));

// Check if driesvints is being sponsored by nunomaduro...
$client->login('driesvints')->isSponsoredBy('nunomaduro');

// Check if the blade-ui-kit organization is sponsored by nunomaduro...
$client->login('nunomaduro')->isSponsoring('blade-ui-kit');

// Check if the authenticated user is sponsored by Gummibeer...
$client->viewer()->isSponsoredBy('Gummibeer');

// Check if the authenticated user is sponsoring by driesvints...
$client->viewer()->isSponsoring('driesvints');
```

## Roadmap

Here's some of the features on our roadmap. We'd always appreciate PR's to kickstart these.

- [Caching](https://github.com/github-php/sponsors/issues/1)
- [Retrieve sponsorships](https://github.com/github-php/sponsors/issues/2)
- [Adopt fluent syntax for client calls](https://github.com/github-php/sponsors/issues/13)
- [Check sponsorship tiers](https://github.com/github-php/sponsors/issues/3)
- [Create new Sponsorships](https://github.com/github-php/sponsors/issues/5)
- [Automatically grant and revoke perks](https://github.com/github-php/sponsors/issues/7)
- [Sync sponsorships through GitHub webhooks](https://github.com/github-php/sponsors/issues/6)
- [Track sponsored amounts](https://github.com/github-php/sponsors/issues/8)

Not seeing the feature you seek? Consider opening up [an issue](https://github.com/github-php/sponsors/issues).

## Requirements

- PHP 7.4 or higher
- Laravel 8.0 or higher (optional when using Laravel)

## Installation

Install the package with composer:

```bash
composer require github-php/sponsors
```

## Updating

Please refer to [`the upgrade guide`](UPGRADE.md) when updating the library.

## Configuration

### Authentication

All of the GitHub GraphQL autentication goes through a [personal access token](https://docs.github.com/en/graphql/guides/forming-calls-with-graphql#authenticating-with-graphql). A token is **always** needed when working with the GitHub GraphQL.

To get started using this library, head over to [your settings screen](https://github.com/settings/tokens) and create a personal access token that has access to the `user:read` and `org:read` scopes. This is the token that you'll use in the code examples below. 

It's important to note that this will be the main point-of-view of how the GraphQL will view sponsorships so make sure to pick the correct user account. For example, if you're Laravel and you need to perform checks to see if anyone is sponsoring Laravel publically or privately, the token should be created under someone who has access to the Laravel organization (like `taylorotwell`).

#### Authentication in Laravel

If you're integrating with Laravel, the package will be set up automatically through Package Discovery. The only thing that's left to do is to set the personal access token in your `.env` file:

```
GH_SPONSORS_TOKEN=ghp_xxx
```

## Usage

### Initializing the client

All of this library's API calls are made from the core `GitHub\Sponsors\Client` class. The client makes use of the [Illuminate HTTP Client](https://laravel.com/docs/http-client) client to perform the API calls. This client needs to be authenticated using the GitHub Personal Access token which you've created in the [authentication](#authentication) step above.

To get started, initialize the GitHub API client, authenticate using the token (preferable through an environment variable) and initialize the Sponsors client:

```php
use GitHub\Sponsors\Client;

$client = new Client(getenv('GH_SPONSORS_TOKEN'));
```

This will be the client we'll use throughout the rest of these docs. We'll re-use the `$client` variable in the below examples.

### Initializing the client using Laravel

If you're using Laravel, the client is already bound to the container as a singleton. Simply retrieve it from the container:

```php
use GitHub\Sponsors\Client;

$client = app(Client::class);
```

The client was authenticated with the env variable you've set in your `.env` file.

### Checking Sponsorships

At its core, this library allows you to easily check wether a specific user or organization is sponsoring another one:

```php
// Check if driesvints is being sponsored by nunomaduro...
$client->login('driesvints')->isSponsoredBy('nunomaduro');

// Check if the blade-ui-kit organization is being sponsored by nunomaduro...
$client->login('blade-ui-kit')->isSponsoredBy('nunomaduro');
```

### Checking Sponsorships as a Viewer

You can also perform these checks from the point-of-view of the user that was used to authenticate the GitHub API client. If you'll use the methods below, it would be as if you'd be browsing GitHub as the user that created the token.

```php
// Is the current authed user sponsoring driesvints?
$client->viewer()->isSponsoring('driesvints');

// Is the current authed user sponsoring the laravel organization?
$client->viewer()->isSponsoring('laravel');

// Is the current authed user sponsored by driesvints?
$client->viewer()->isSponsoredBy('driesvints');

// Is the current authed user sponsored by the laravel organization?
$client->viewer()->isSponsoredBy('laravel');
```

You might be wondering why we're using the "Viewer" wording here. "Viewer" is also a concept in the GraphQL API of GitHub. It represents the currently authenticated user that's performing the API requests. That's why we've decided to also use this terminology in the package's API.

### Checking Sponsorships with a Facade

If you use Laravel you can also make use of the shipped `GitHubSponsors` facade:

```php
use GitHub\Sponsors\Facades\GitHubSponsors;

// Check if driesvints is being sponsored by nunomaduro...
GitHubSponsors::login('driesvints')->isSponsoredBy('nunomaduro');

// Check if the blade-ui-kit organization is being sponsored by nunomaduro...
GitHubSponsors::login('blade-ui-kit')->isSponsoredBy('nunomaduro');
```

### Sponsorable Behavior

PHP GitHub Sponsors ships with a `Sponsorable` trait that can add sponsorable behavior to an object. Let's say you have a `User` object in your app. By letting that user provide a personal access token of their own, you can perform sponsorship checks on them as if they were browsing GitHub themselves.

#### The `Sponsorable` trait

To get started, add the trait to any object you want to use it on and set the user's GitHub username and their personal access token:

```php
use GitHub\Sponsors\Concerns\Sponsorable;
use GitHub\Sponsors\Contracts\Sponsorable as SponsorableContract;

class User implements SponsorableContract
{
    use Sponsorable;

    private string $github;

    private string $github_token;

    public function __construct(string $github, string $github_token)
    {
        $this->github = $github;
        $this->github_token = $github_token;
    }
}
```

Notice that we also added the `GitHub\Sponsors\Contracts\Sponsorable` to make sure the API is properly implemented on the `User` class.

The `$github_token` can be the same personal access token you use to initialize the `GitHub\Sponsors\Client` class but **if you also want to check private sponsorships on the user** you'll need them to provide you with their own token.

> ⚠️ Note that there is no check being performed on wether the github username and a user provided personal access token belong together. This is your own responsibility to do through [an API call to GitHub](https://docs.github.com/en/graphql/reference/queries#user). 

#### Using the sponsorable

Now that we've configured our object, we can use it to perform GitHub Sponsors checks against:

```php
$user = new User('driesvints', getenv('GH_SPONSORS_TOKEN'));

// Check if driesvints is being sponsored by nunomaduro...
$user->isSponsoredBy('nunomaduro');

// Check if driesvints is being sponsored by the blade-ui-kit organization...
$user->isSponsoredBy('blade-ui-kit');

// Check if driesvints is sponsoring nunomaduro...
$user->isSponsoring('nunomaduro');

// Check if driesvints is sponsoring spatie...
$user->isSponsoring('spatie');
```

#### Using the `Sponsorable` trait with Eloquent

If your sponsorable is an Eloquent model from Laravel, the setup differs a bit:

```php
use GitHub\Sponsors\Concerns\Sponsorable;
use GitHub\Sponsors\Contracts\Sponsorable as SponsorableContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements SponsorableContract
{
    use Sponsorable;
}
```

What's important is that there's a `github` column (`string`) on the model's table. This column will need to have the GitHub username that belongs to the model.

With an Eloquent model, you also don't need to pass a personal access token. By default, it'll use the `GitHub\Sponsors\Client` class that's bound to the container. If you do want to identify the sponsorable to also check their private sponsorships you can add a `github_token` column (`string`) to the model's table and make sure the value is filled in. That way, all API requests will behave as if the user themselves is doing it.

> ⚠️ Note that there is no check being performed on wether the github username and a user provided personal access token belong together. This is your own responsibility to do through [an API call to GitHub](https://docs.github.com/en/graphql/reference/queries#user). 

And then you can use the model as follows:

```php
$user = User::where('github', 'driesvints')->first();

// Check if driesvints is being sponsored by nunomaduro...
$user->isSponsoredBy('nunomaduro');
```

#### Customizing the Sponsorable properties

If you want to customize the `$github` & `$github_token` property names you'll also need to update their getters:

```php
use GitHub\Sponsors\Concerns\Sponsorable;
use GitHub\Sponsors\Contracts\Sponsorable as SponsorableContract;

class User implements SponsorableContract
{
    use Sponsorable;

    private string $gitHubUsername;

    private string $gitHubToken;

    public function __construct(string $gitHubUsername, string $gitHubToken)
    {
        $this->gitHubUsername = $gitHubUsername;
        $this->gitHubToken = $gitHubToken;
    }

    public function gitHubUsername(): string
    {
        return $this->gitHubUsername;
    }

    public function gitHubToken(): ?string
    {
        return $this->gitHubToken;
    }
}
```

#### Customizing the Sponsorable client

When providing the sponsorable with a token, it'll initialize a new GitHub client. You may also provide [the pre-set client](#initializing-the-client) if you wish:

```php
use GitHub\Sponsors\Client;
use GitHub\Sponsors\Concerns\Sponsorable;
use GitHub\Sponsors\Contracts\Sponsorable as SponsorableContract;
use GitHub\Sponsors\Login;

class User implements SponsorableContract
{
    use Sponsorable;

    private Client $client;

    private string $github;

    public function __construct(Client $client, string $github)
    {
        $this->client = $client;
        $this->github = $github;
    }

    protected function sponsorsClient(): Login
    {
        return $this->client->login($this->gitHubUsername());
    }
}
```

## Tutorials

### Usage in Laravel Policies

PHP GitHub Sponsors is an ideal way to grant your users access to certain resources in your app. Therefor, it's also an ideal candidate for a Laravel policy. For example, you could write a policy that grants access to a product when a user is sponsoring you.

First, you'll have to set [the `GH_SPONSORS_TOKEN` in your `.env` file](#initializing-the-client-using-laravel). This token needs to be created by the user that's being sponsored or a user that is a member of the organization that's being sponsored. Then, the client will be authenticated with this token.

Next, you'll need to [add the `Sponsorable` trait to your `User` model](#using-the-sponsorable-trait-with-eloquent). Additionally, you'll need to make sure that the `users` database has a `github` column (`VARCHAR(255)`) and all users have their GitHub usernames filled out.

Then, we'll write out policy. Let's say that we're creating this policy for Spatie:

```php
<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if a product can be reached by the user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->isSponsoring('spatie');
    }
}
```

We wire up the policy in the `AuthServiceProvider` of our app:

```php
use App\Models\Product;
use App\Policies\ProductPolicy;

/**
 * The policy mappings for the application.
 *
 * @var array
 */
protected $policies = [
    Product::class => ProductPolicy::class,
];
```

And now we can use the policy to do ACL checks to see if the authenticated user can access Spatie's products:

```blade
@can('view', App\Models\Product::class)
    <a href="{{ route('products') }}">
        View Products
    </a>
@else
    <a href="https://github.com/sponsors/spatie">
        Sponsor us to use our products!
    </a> 
@endcan
```

And that's it. Of course, you'd probably also want to protect any controller giving access to the `products` route.

## FAQ

### Why is the sponsorship check returning `false` for private sponsorship checks?

The way the GitHub GraphQL mostly works is [through personal access tokens](https://docs.github.com/en/graphql/guides/forming-calls-with-graphql#authenticating-with-graphql). Because these tokens are always created from a specific user in GitHub, the API calls will return results based on the visibility of the user and their access to the target resource.

For example, if I as `driesvints` were to privately sponsor `spatie` I could do an `isSponsoredBy('driesvints', 'spatie')` check and it would return `true` for me because I have access to my account through my personal access token that was created on `driesvints`. But if `nunomaduro` would be privately sponsoring `spatie` and I was to attempt `isSponsoredBy('nunomaduro', 'spatie')` with the token created on `driesvints`, it will return false because I don't have access to `nunomaduro`'s account. 

It is also important that if you're checking against organizations that you're using a token of a user that is a member of the organization. Any other GitHub user will not have access to check private sponsorships for that organization.

Public sponsorships will always be visible though, regardless on which user the token was created.

### Why are the `user:read` and `org:read` scopes needed?

These are both needed to authenticate the GitHub client to perform checks on a user's private sponsorships. Since by default these are hidden from any public API call, we need to explicitely grant consumers of the token permission to read these.

## Changelog

Check out the [CHANGELOG](CHANGELOG.md) in this repository for all the recent changes.

## Maintainers

PHP GitHub Sponsors is developed and maintained by [Dries Vints](https://driesvints.com) and [Tom Witkowski](https://gummibeer.dev).

## License

PHP GitHub Sponsors is open-sourced software licensed under [the MIT license](LICENSE.md).
