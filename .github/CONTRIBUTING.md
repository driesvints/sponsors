# Contributing to PHP GitHub Sponsors

All contributions are welcome. If you want to ask or propose something please create an issue first. If you want to contribute please send in a pull request.

## Pull Requests

- Follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) coding standards
- Write tests for new functionality or bug fixes
- Keep the `README.md` file and documentation up-to-date with changes
- We follow [Semantic Versioning](http://semver.org/) so please send pull requests to the correct branch
- Update the `CHANGELOG.md` file with any updates and follow [the changelog standards](http://keepachangelog.com/)

## Running Tests

You'll need to create a `phpunit.xml` file in the root of the package with the following code:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit colors="true">
    <testsuites>
        <testsuite name="Package Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="GH_SPONSORS_TOKEN" value="ghp_xxx"/>
    </php>
</phpunit>
```

Fill out the `GH_SPONSORS_TOKEN` with [a personal access token](https://github.com/settings/tokens) that has the `user:read` and `org:read` scopes.

You can run the tests with the following command:

```bash
$ vendor/bin/phpunit --exclude-group Private
```

The reason why the [`PrivateSponsorsTest.php`](../tests/PrivateSponsorsTest.php) test is skipped is because it needs to use an access token from the [@driesvints](https://github/driesvints) GitHub user account. This test will be run by the package's CI build when you send in a pull request.
