<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Google\Analytics\Data\V1beta\DimensionHeader;
use Google\Analytics\Data\V1beta\DimensionValue;
use Google\Analytics\Data\V1beta\MetricHeader;
use Google\Analytics\Data\V1beta\MetricValue;
use Google\Analytics\Data\V1beta\Row;
use Google\Analytics\Data\V1beta\RunReportResponse;

class LaravelAnalyticsV4
{
    protected LaravelAnalyticsV4Client $client;

    protected bool $shouldConvertResponseToArray = true;

    public function __construct(LaravelAnalyticsV4Client $client)
    {
        $this->client = $client;
    }

    /**
     * Enable\Disable unwrapping to an array. Disabling this will return original response from the Analytics data v1 api
     *
     * @param  bool  $convert
     * @return $this
     */
    public function convertResponseToArray(bool $convert = true): static
    {
        $this->shouldConvertResponseToArray = $convert;

        return $this;
    }

    /**
     * I dont like the GA return types so I'm going to convert it all to static data
     *
     * @param  RunReportResponse  $response
     * @return array
     */
    public function unwrapToArray(RunReportResponse $response): array
    {
        $dimensionHeaders = collect($response->getDimensionHeaders())->map(function (DimensionHeader $header) {
            return $header->getName();
        })->toArray();

        $metricHeaders = collect($response->getMetricHeaders())->map(function (MetricHeader $header) {
            return $header->getName();
        })->toArray();

        return collect($response->getRows())->map(function (Row $row) use ($dimensionHeaders, $metricHeaders) {
            $finalData = [];

            collect($row->getDimensionValues())->map(function (DimensionValue $rowValue, $rowIndex) use (&$finalData, $dimensionHeaders) {
                $finalData['dimensions'][$dimensionHeaders[$rowIndex]] = $rowValue->getValue();
            });

            collect($row->getMetricValues())->each(function (MetricValue $metricValue, $rowIndex) use (&$finalData, $metricHeaders) {
                $finalData['metrics'][$metricHeaders[$rowIndex]] = $metricValue->getValue();
            });

            return $finalData;
        })->toArray();
    }

    /**
     * Runs the report with the given configuration
     *
     * @param  RunReportConfiguration  $configuration
     * @return RunReportResponse|array
     */
    public function runReport(RunReportConfiguration $configuration): RunReportResponse | array
    {
        if (! $this->shouldConvertResponseToArray) {
            return $this->client->runReport($configuration->toGoogleObject());
        }

        return $this->unwrapToArray($this->client->runReport($configuration->toGoogleObject()));
    }
}
