<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Exceptions\BadCredentialsException;
use GitHub\Sponsors\Exceptions\QueryException;
use Illuminate\Http\Client\Factory;

final class GitHubSponsors
{
    private Factory $http;

    private string $token;

    public function __construct(Factory $http, string $token)
    {
        $this->http = $http;
        $this->token = $token;
    }

    public function isSponsoredBy(string $account, string $sponsor): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
                $sponsor: String!
            ) {
                user(login: $account) {
                    isSponsoredBy(accountLogin: $sponsor)
                }
                organization(login: $account) {
                    isSponsoredBy(accountLogin: $sponsor)
                }
            }
        QUERY;

        $result = $this->graphql($query, compact('account', 'sponsor'));

        return ($result['user']['isSponsoredBy'] ?? false) ||
            ($result['organization']['isSponsoredBy'] ?? false);
    }

    public function isViewerSponsoring(string $account): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
            ) {
                user(login: $account) {
                    viewerIsSponsoring
                }
                organization(login: $account) {
                    viewerIsSponsoring
                }
            }
        QUERY;

        $result = $this->graphql($query, compact('account'));

        return ($result['user']['viewerIsSponsoring'] ?? false) ||
            ($result['organization']['viewerIsSponsoring'] ?? false);
    }

    public function isViewerSponsoredBy(string $sponsor): bool
    {
        $query = <<<'QUERY'
            query (
                $sponsor: String!
            ) {
                user(login: $sponsor) {
                    isSponsoringViewer
                }
                organization(login: $sponsor) {
                    isSponsoringViewer
                }
            }
        QUERY;

        $result = $this->graphql($query, compact('sponsor'));

        return ($result['user']['isSponsoringViewer'] ?? false) ||
            ($result['organization']['isSponsoringViewer'] ?? false);
    }

    private function graphql($query, array $variables = []): array
    {
        $response = $this->http
            ->withToken($this->token)
            ->asJson()
            ->post('https://api.github.com/graphql', [
                'query' => $query,
                'variables' => $variables,
            ]);

        if ($response->status() === 401) {
            throw BadCredentialsException::badToken();
        }

        if ($response->clientError()) {
            throw QueryException::badQuery();
        }

        return $response->json('data');
    }
}
