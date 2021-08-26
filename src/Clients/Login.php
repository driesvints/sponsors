<?php

namespace GitHub\Sponsors\Clients;

use GitHub\Sponsors\Contracts\Sponsorable;
use GitHub\Sponsors\GraphqlClient;

final class Login implements Sponsorable
{
    private GraphqlClient $client;

    private string $login;

    public function __construct(GraphqlClient $client, string $login)
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