<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

class LaravelAnalyticsV4
{
    protected LaravelAnalyticsV4Client $client;

    public function __construct(LaravelAnalyticsV4Client $client)
    {
        $this->client = $client;
    }

    public function runReport(RunReportConfiguration $configuration)
    {
        $this->client->runReport($configuration->toArray());
    }
}
