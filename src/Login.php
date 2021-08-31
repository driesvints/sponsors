<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use Generator;
use GitHub\Sponsors\Contracts\Sponsorable;
use Illuminate\Support\LazyCollection;

final class Login implements Sponsorable
{
    private Client $client;

    private string $login;

    public function __construct(Client $client, string $login)
    {
        $this->client = $client;
        $this->login = $login;
    }

    public function isSponsoredBy(string $sponsor): bool
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

        $result = $this->client->send($query, [
            'account' => $this->login,
            'sponsor' => $sponsor,
        ]);

        return ($result['user']['isSponsoredBy'] ?? false) ||
            ($result['organization']['isSponsoredBy'] ?? false);
    }

    public function isSponsoring(string $account): bool
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

        $result = $this->client->send($query, [
            'account' => $account,
            'sponsor' => $this->login,
        ]);

        return ($result['user']['isSponsoredBy'] ?? false) ||
            ($result['organization']['isSponsoredBy'] ?? false);
    }

    public function hasSponsors(): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
            ) {
                user(login: $account) {
                    sponsorshipsAsMaintainer {
                        totalCount
                    }
                }
                organization(login: $account) {
                    sponsorshipsAsMaintainer {
                        totalCount
                    }
                }
            }
        QUERY;

        $result = $this->client->send($query, [
            'account' => $this->login,
        ]);

        return ($result['user']['sponsorshipsAsMaintainer']['totalCount'] ?? $result['organization']['sponsorshipsAsMaintainer']['totalCount']) > 0;
    }

    public function sponsors(array $select = ['login']): LazyCollection
    {
        $fields = implode(PHP_EOL, $select);

        $query = <<<QUERY
            query (
                \$login: String!
                \$userCursor: String
                \$organizationCursor: String
            ) {
                user(login: \$login) {
                    sponsorshipsAsMaintainer(first: 100, after: \$userCursor) {
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
                organization(login: \$login) {
                    sponsorshipsAsMaintainer(first: 100, after: \$organizationCursor) {
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
            $userCursor = null;
            $organizationCursor = null;

            do {
                $data = $this->client->send($query, [
                    'login' => $this->login,
                    'userCursor' => $userCursor,
                    'organizationCursor' => $organizationCursor,
                ]);
                $userCursor = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['endCursor'] ?? null;
                $organizationCursor = $data['organization']['sponsorshipsAsMaintainer']['pageInfo']['endCursor'] ?? null;

                $hasNextPage = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['hasNextPage']
                    ?? $data['organization']['sponsorshipsAsMaintainer']['pageInfo']['hasNextPage']
                    ?? false;

                yield from array_column(
                    $data['user']['sponsorshipsAsMaintainer']['nodes']
                        ?? $data['organization']['sponsorshipsAsMaintainer']['nodes']
                        ?? [],
                    'sponsorEntity'
                );
            } while ($hasNextPage && ($userCursor || $organizationCursor));
        });
    }
}
