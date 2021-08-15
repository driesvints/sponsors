# PHP GitHub Sponsors

<a href="https://github.com/driesvints/php-github-sponsors/actions?query=workflow%3ATests">
    <img src="https://github.com/driesvints/php-github-sponsors/workflows/Tests/badge.svg" alt="Tests">
</a>
<a href="https://github.styleci.io/repos/371488434">
    <img src="https://github.styleci.io/repos/371488434/shield?style=flat" alt="Code Style">
</a>
<a href="https://packagist.org/packages/driesvints/php-github-sponsors">
    <img src="https://img.shields.io/packagist/v/driesvints/php-github-sponsors" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/driesvints/php-github-sponsors">
    <img src="https://img.shields.io/packagist/driesvints/php-github-sponsors" alt="Total Downloads">
</a>

PHP GitHub Sponsors is a package that integrates directly with [the GitHub Sponsors GraphQL API](https://docs.github.com/en/sponsors/integrating-with-github-sponsors/getting-started-with-the-sponsors-graphql-api). Using it, you can easily check if a GitHub account is sponsoring another account. This helps you implement powerful ACL capibilities in your application and the ability to grant users access to specific resources when they sponsor you.

The library is PHP agnostic but provides deep integration with [Laravel](https://laravel.com).

Here's an example how you'd use it:

```php
use Dries\Sponsors\Sponsors;
use GitHub\Client as GitHub;

$github = new GitHub();
$github->authenticate(getenv('GH_SPONSORS_TOKEN'), null, GitHub::AUTH_ACCESS_TOKEN);

$sponsors = new Sponsors($github);

// Check if driesvints is being sponsored by nunomaduro...
$sponsors->isSponsoredBy('driesvints', 'nunomaduro');

// Check if the blade-ui-kit organization is being sponsored by nunomaduro...
$sponsors->isOrganizationSponsoredBy('blade-ui-kit', 'nunomaduro');
```

## Planned features

Here's some of the features on our roadmap. We'd always appreciate PR's to kickstart these.

- Caching
- Retrieve sponsorships
- Check sponsorship tiers
- Automatically grant and revoke perks
- Track sponsored amounts

Not seeing the feature you seek? Consider opening up [an issue](https://github.com/driesvints/php-github-sponsors/issues).

## Requirements

- PHP 7.4 or higher
- Laravel 8.0 or higher (optional when using Laravel)

## Installation

Install the package with composer:

```bash
composer require driesvints/php-github-sponsors
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

All of this library's API calls are made from the core `Dries\Sponsors\Sponsors` client. The client makes use of the [PHP GitHub API](https://github.com/KnpLabs/php-github-api) client to perform the API calls. This client needs to be authenticated using the GitHub Personal Access token which you've created in the [authentication](#authentication) step above.

To get started, initialize the GitHub API client, authenticate using the token (preferable through an environment variable) and initialize the Sponsors client:

```php
use Dries\Sponsors\Sponsors;
use GitHub\Client as GitHub;

$github = new GitHub();
$github->authenticate(getenv('GH_SPONSORS_TOKEN'), null, GitHub::AUTH_ACCESS_TOKEN);

$sponsors = new Sponsors($github);
```

This will be the client we'll use throughout the rest of these docs. To save space, we won't be repeating this step. Instead we'll re-use the `$sponsors` variable in the below examples.

### Initializing the client using Laravel

If you're using Laravel, the client is already bound to the container as a singleton. Simply retrieve it from the container:

```php
use Dries\Sponsors\Sponsors;

$sponsors = app(Sponsors::class);
```

The client was authenticated with the env variable you've set in your `.env` file.

### Checking Sponsorships

At its core, this library allows you to easily check wether a specific user or organization is sponsoring another one:

```php
// Check if driesvints is being sponsored by nunomaduro...
$sponsors->isSponsoredBy('driesvints', 'nunomaduro');

// Check if the blade-ui-kit organization is being sponsored by nunomaduro...
$sponsors->isOrganizationSponsoredBy('blade-ui-kit', 'nunomaduro');
```

These are all simply boolean checks. Note that while we need to know beforehand if the account the check is happening on is a regular GitHub user or a GitHub organization, we do not need to know if the sponsor is a user or an organization.

### Checking Sponsorships as a Viewer

You can also perform these checks from a point-of-view as the personal access token that was used to authenticate the GitHub API client. If you'll use the methods below, it would be as if you'd be browsing GitHub as the user that created the token.

```php
// Is the current authed user sponsoring driesvints?
$sponsors->isViewerSponsoring('driesvints');

// Is the current authed user sponsoring the laravel organization?
$sponsors->isViewerSponsoringOrganization('laravel');

// Is the current authed user sponsored by driesvints?
$sponsors->isViewerSponsoredBy('driesvints');

// Is the current authed user sponsored by the laravel organization?
$sponsors->isViewerSponsoredByOrganization('laravel');
```

This is a bit the reverse of the examples above this section. Because of a limitation on the GitHub GraphQL API, you'll always need to know beforehand if the target is a regular user account or an organization.

### Checking Sponsorships with a Facade

If you use Laravel you can also make use of the shipped `Sponsors` facade:

```php
// Check if driesvints is being sponsored by nunomaduro...
Sponsors::isSponsoredBy('driesvints', 'nunomaduro');

// Check if the blade-ui-kit organization is being sponsored by nunomaduro...
Sponsors::isOrganizationSponsoredBy('blade-ui-kit', 'nunomaduro');
```

### Sponsorable Behavior

PHP GitHub Sponsors ships with a `Sponsorable` trait that can add sponsorable behavior to an object. Let's say you have a `User` object in your app. By letting that user provide a personal access token of their own, you can perform sponsorship checks on them as if they were browsing GitHub themselves.

#### The `Sponsorable` trait

To get started, add the trait to any object you want to use it on and set the user's GitHub username and their personal access token:

```php
use Dries\Sponsors\Concerns\Sponsorable;

class User
{
    use Sponsorable;

    public function __construct(
        private string $github,
        private string $github_token
    ) {}
}
```

The `$github_token` can be the same personal access token you use to initialize the GitHub API Client but **if you also want to check private sponsorships on the user** you'll need them to provide you with their own token.

> ⚠️ Note that there is no check being performed on wether the github username and a user provided personal access token belong together. This is your own responsibility to do through [an API call to GitHub](https://docs.github.com/en/graphql/reference/queries#user). 

#### Using the sponsorable

Now that we've configured our object, we can use it to perform GitHub Sponsors checks against:

```php
$user = new User('driesvints', getenv('GH_SPONSORS_TOKEN'));

// Check if driesvints is being sponsored by nunomaduro...
$user->isSponsoredBy('nunomaduro');

// Check if driesvints is being sponsored by blade-ui-kit...
$user->isSponsoredByOrganization('blade-ui-kit');

// Check if driesvints is sponsoring nunomaduro...
$user->isSponsoring('nunomaduro');

// Check if driesvints is sponsoring spatie...
$user->isSponsoringOrganization('spatie');
```

#### Using the sponsorable with Eloquent

If your sponsorable is an Eloquent model from Laravel, the integration differs a bit:

```php
use Dries\Sponsors\Concerns\Sponsorable;
use Illuminate\Database\Eloquent\Model;

class User extends Model;
{
    use Sponsorable;
}
```

What's important is that there's a `github` column (`string`) on the model's table. This column will need to have the GitHub username that belongs to the model.

With an Eloquent model, you also don't need to pass a personal access token. By default, it'll use the GitHub Sponsors client that's bound to the container. If you do want to identify the sponsorable to also check their private sponsorships you can add a `github_token` column (`string`) to the model's table and make sure the value is filled in. That way, all API requests will behave as if the user themselves is doing it.

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
use Dries\Sponsors\Concerns\Sponsorable;

class User
{
    use Sponsorable;

    public function __construct(
        private string $gitHubUsername,
        private string $gitHubToken
    ) {}

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

#### Organization Sponsorables

If, instead of a user, you want to use the `Sponsorable` trait on an organization, you'll need to identify the sponsorable as such:

```php
use Dries\Sponsors\Concerns\Sponsorable;

class Organization
{
    use Sponsorable;

    // ...

    public function isGitHubOrganization(): bool
    {
        return true;
    }
}
```

> ⚠️ It is important that the organization is provided with a personal access token of a GitHub user that has access to the organization.

#### Customizing the Sponsorable client

When providing the sponsorable with a token, it'll initialize a new GitHub client. You may also provide [the pre-set client](#initializing-the-client) if you wish:

```php
use Dries\Sponsors\Concerns\Sponsorable;
use Dries\Sponsors\Sponsors;

class User
{
    use Sponsorable;

    public function __construct()
    {
        private Sponsors $sponsors,
        private string $github
    }

    protected function sponsorsClient(): Sponsors
    {
        return $this->sponsors;
    }
}
```

## FAQ

### Why are there separate organization methods?

The GitHub GraphQL API was designed in a way that there's a differentation between user accounts and organization accounts. Because we need to be able to perform sponsorship checks on both of them we both need to use [the `user` query](https://docs.github.com/en/graphql/reference/queries#user) as well as [the `organization` query](https://docs.github.com/en/graphql/reference/queries#organization). Therefor, we need to know beforehand if the entity we're doing the sponsorship check against is either a user or an organization.

### Why is the sponsorship check returning `false` for private sponsorship checks?

The way the GitHub GraphQL mostly works is [through personal access tokens](https://docs.github.com/en/graphql/guides/forming-calls-with-graphql#authenticating-with-graphql). Because these tokens are always created from a specific user in GitHub, the API calls will return results based on the visibility of the user and their access to the target resource.

For example, if I as `driesvints` were to privately sponsor `spatie` I could do an `isSponsoredBy('driesvints', 'spatie')` check and it would return `true` for me because I have access to my account through my personal access token that was created on `driesvints`. But if `nunomaduro` would be privately sponsoring `spatie` and I was to attempt `isSponsoredBy('nunomaduro', 'spatie')` with the token created on `driesvints`, it will return false because I don't have access to `nunomaduro`'s account. 

Public sponsorships will always be visible though, regardless on which the token was created.

### Why are the `user:read` and `org:read` scopes needed?

These are both needed to authenticate the GitHub client to perform checks on a user's private sponsorships. Since by default these are hidden from any public API call, we need to explicitely grant consumers of the token permission to read these.

## Changelog

Check out the [CHANGELOG](CHANGELOG.md) in this repository for all the recent changes.

## Maintainers

PHP GitHub Sponsors is developed and maintained by [Dries Vints](https://driesvints.com).

## License

PHP GitHub Sponsors is open-sourced software licensed under [the MIT license](LICENSE.md).
