<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class LaravelAnalyticsV4Client
{
    protected string $propertyId;
    protected BetaAnalyticsDataClient $client;

    public function setProperty(string $propertyId) : LaravelAnalyticsV4Client
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function setGoogleClient(BetaAnalyticsDataClient $client)
    {
        $this->client = $client;

        return $this;
    }

    public function runReport(array $configuration)
    {
        return $this->client->runReport(array_merge([
            'property' => "properties/{$this->propertyId}"
        ], $configuration));
    }
}
