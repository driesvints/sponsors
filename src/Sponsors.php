<?php

declare(strict_types=1);

namespace Dries\Sponsors;

use Github\Client as GitHub;

final class Sponsors
{
    public function __construct(
        private GitHub $github
    ) {}

    public function isSponsoredBy(string $account, string $sponsor, bool $isAccountAnOrganization = false): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
                $sponsor: String!
            ) {
                %s(login: $account) {
                    isSponsoredBy(accountLogin: $sponsor)
                }
            }
        QUERY;

        if ($isAccountAnOrganization) {
            $result = $this->queryOrganization($query, compact('account', 'sponsor'), 'isSponsoredBy');
        } else {
            $result = $this->queryUser($query, compact('account', 'sponsor'), 'isSponsoredBy');
        }

        return $result ?? false;
    }

    public function isOrganizationSponsoredBy(string $account, string $sponsor): bool
    {
        return $this->isSponsoredBy($account, $sponsor, true);
    }

    public function isViewerSponsoring(string $account, bool $isAccountAnOrganization = false): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
            ) {
                %s(login: $account) {
                    viewerIsSponsoring
                }
            }
        QUERY;

        if ($isAccountAnOrganization) {
            $result = $this->queryOrganization($query, compact('account'), 'viewerIsSponsoring');
        } else {
            $result = $this->queryUser($query, compact('account'), 'viewerIsSponsoring');
        }

        return $result ?? false;
    }

    public function isViewerSponsoringOrganization(string $account): bool
    {
        return $this->isViewerSponsoring($account, true);
    }

    public function isViewerSponsoredBy(string $sponsor, bool $isSponsorAnOrganization = false): bool
    {
        $query = <<<'QUERY'
            query (
                $sponsor: String!
            ) {
                %s(login: $sponsor) {
                    isSponsoringViewer
                }
            }
        QUERY;

        if ($isSponsorAnOrganization) {
            $result = $this->queryOrganization($query, compact('sponsor'), 'isSponsoringViewer');
        } else {
            $result = $this->queryUser($query, compact('sponsor'), 'isSponsoringViewer');
        }

        return $result ?? false;
    }

    public function isViewerSponsoredByOrganization(string $sponsor): bool
    {
        return $this->isViewerSponsoredBy($sponsor, true);
    }

    private function queryUser(string $query, array $variables, string $field)
    {
        return $this->query($query, $variables, 'user', $field);
    }

    private function queryOrganization(string $query, array $variables, string $field)
    {
        return $this->query($query, $variables, 'organization', $field);
    }

    private function query(string $query, array $variables, string $type, string $field)
    {
        $query = sprintf($query, $type);

        return $this->graphql($query, $variables)[$type][$field];
    }

    private function graphql($query, array $variables = []): array
    {
        return $this->github->api('graphql')->execute($query, $variables)['data'];
    }
}
