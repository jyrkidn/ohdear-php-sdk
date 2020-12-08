<?php

namespace OhDear\PhpSdk;

use Carbon\Carbon;
use GuzzleHttp\Client;
use OhDear\PhpSdk\Actions\ManagesBrokenLinks;
use OhDear\PhpSdk\Actions\ManagesCertificateHealth;
use OhDear\PhpSdk\Actions\ManagesChecks;
use OhDear\PhpSdk\Actions\ManagesCronChecks;
use OhDear\PhpSdk\Actions\ManagesDowntime;
use OhDear\PhpSdk\Actions\ManagesMaintenancePeriods;
use OhDear\PhpSdk\Actions\ManagesMixedContent;
use OhDear\PhpSdk\Actions\ManagesPerformance;
use OhDear\PhpSdk\Actions\ManagesSites;
use OhDear\PhpSdk\Actions\ManagesStatusPages;
use OhDear\PhpSdk\Actions\ManagesUptime;
use OhDear\PhpSdk\Actions\ManagesUsers;

class OhDear
{
    use MakesHttpRequests,
        ManagesSites,
        ManagesChecks,
        ManagesUsers,
        ManagesBrokenLinks,
        ManagesMaintenancePeriods,
        ManagesMixedContent,
        ManagesUptime,
        ManagesDowntime,
        ManagesCertificateHealth,
        ManagesStatusPages,
        ManagesPerformance,
        ManagesCronChecks;

    /** @var string */
    public $apiToken;

    public $client;

    public function __construct(string $apiToken, string $baseUri = 'https://ohdear.app/api/')
    {
        $this->apiToken = $apiToken;

        $this->client = new Client([
            'base_uri' => $baseUri,
            'http_errors' => false,
            'verify' => false,
            'headers' => [
                'Authorization' => "Bearer {$this->apiToken}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    protected function transformCollection(array $collection, string $class): array
    {
        return array_map(function ($attributes) use ($class) {
            return new $class($attributes, $this);
        }, $collection);
    }

    public function convertDateFormat(string $date, $format = 'YmdHis'): string
    {
        return Carbon::parse($date)->format($format);
    }
}
