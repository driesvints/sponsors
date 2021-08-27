<?php

declare(strict_types=1);

namespace GitHub\Sponsors;

use GitHub\Sponsors\Contracts\Sponsorable;

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
}