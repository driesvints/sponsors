<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Contracts\Sponsorable;

final class Viewer implements Sponsorable
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function isSponsoredBy(string $sponsor): bool
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

        $result = $this->client->send($query, compact('sponsor'));

        return ($result['user']['isSponsoringViewer'] ?? false) ||
            ($result['organization']['isSponsoringViewer'] ?? false);
    }

    public function isSponsoring(string $account): bool
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

        $result = $this->client->send($query, compact('account'));

        return ($result['user']['viewerIsSponsoring'] ?? false) ||
            ($result['organization']['viewerIsSponsoring'] ?? false);
    }
}
