<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use Generator;
use GitHub\Sponsors\Contracts\Sponsorable;
use Illuminate\Support\LazyCollection;

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

    public function hasSponsors(): bool
    {
        $query = <<<'QUERY'
            query {
                viewer {
                    sponsorshipsAsMaintainer {
                        totalCount
                    }
                }
            }
        QUERY;

        $result = $this->client->send($query);

        return $result['viewer']['sponsorshipsAsMaintainer']['totalCount'] > 0;
    }

    public function sponsors(array $select = ['login']): LazyCollection
    {
        $fields = implode(PHP_EOL, $select);

        $query = <<<QUERY
            query (
                \$cursor: String
            ) {
                viewer {
                    sponsorshipsAsMaintainer(first: 100, after: \$cursor) {
                        pageInfo {
                            hasNextPage
                            endCursor
                        }
                        nodes {
                            sponsorEntity {
                                __typename
                                ... on User {
                                    {$fields}
                                }
                                ... on Organization {
                                    {$fields}
                                }
                            }
                        }
                    }
                }
            }
        QUERY;

        return LazyCollection::make(function () use ($query): Generator {
            $cursor = null;

            do {
                $data = $this->client->send($query, [
                    'cursor' => $cursor,
                ])['viewer']['sponsorshipsAsMaintainer'];
                $cursor = $data['pageInfo']['endCursor'];
                $hasNextPage = $data['pageInfo']['hasNextPage'] ?? false;

                yield from array_column($data['nodes'], 'sponsorEntity');
            } while ($hasNextPage && $cursor);
        });
    }
}
