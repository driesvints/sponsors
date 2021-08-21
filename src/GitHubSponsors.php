<?php

declare(strict_types=1);

namespace Dries\GitHubSponsors;

use Dries\GitHubSponsors\Exceptions\BadCredentialsException;
use Dries\GitHubSponsors\Exceptions\QueryException;
use Illuminate\Http\Client\Factory;

final class GitHubSponsors
{
    public function __construct(
        private Factory $http,
        private string $token
    ) {
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
            throw BadCredentialsException::fromHttpResponse($response);
        }

        if ($response->clientError()) {
            throw QueryException::fromHttpResponse($response);
        }

        return $response->json('data');
    }
}
