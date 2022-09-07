<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class LaravelAnalyticsV4Factory
{
    public static function createFromConfiguration(array $analyticsConfiguration)
    {
        $client = new BetaAnalyticsDataClient([
            'credentials' => self::readCredentials($analyticsConfiguration['service_account_credentials_json']),
        ]);

        $analyticsClient = (new LaravelAnalyticsV4Client())
            ->setProperty($analyticsConfiguration['property_id'])
            ->setGoogleClient($client);

        return new LaravelAnalyticsV4($analyticsClient);
    }

    public static function readCredentials(string $credentialLocation): array
    {
        return json_decode(file_get_contents($credentialLocation), true);
    }
}
