<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Illuminate\Support\Str;
use Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidDimensionException;
use Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidMetricException;
use Myoutdeskllc\LaravelAnalyticsV4\Filters\DimensionFilter;

class RunReportConfiguration
{
    protected string $startDate;

    protected string $endDate;

    protected array $dimensions = [];

    protected array $metrics = [];

    protected array $orderByMetrics = [];

    protected array $orderByDimensions = [];

    protected int $limit = -1;

    protected int $offset = -1;

    /** @var DimensionFilter[] */
    protected array $filters = [];

    protected string $filterMethod = 'and_group';

    public function setStartDate(string $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate(string $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function addDimension(string $dimension): static
    {
        if (! Str::contains($dimension, ':') && ! in_array($dimension, AnalyticsDimensions::getAvailableDimensions())) {
            throw new InvalidDimensionException($dimension.' is not a valid dimension or custom dimension for GA4');
        }

        $this->dimensions[] = [
            'name' => $dimension,
        ];

        return $this;
    }

    public function addDimensions(array $dimensions): static
    {
        foreach ($dimensions as $dimension) {
            $this->addDimension($dimension);
        }

        return $this;
    }

    public function addMetric(string $metric): static
    {
        if (! Str::contains($metric, ':') && ! in_array($metric, AnalyticsMetrics::getAvailableMetrics())) {
            throw new InvalidMetricException($metric.' is not a valid dimension or custom dimension for GA4');
        }

        $this->metrics[] = [
            'name' => $metric,
        ];

        return $this;
    }

    public function addMetrics(array $metrics): static
    {
        foreach ($metrics as $metric) {
            $this->addMetric($metric);
        }

        return $this;
    }

    public function addFilter(DimensionFilter $filter): static
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function addAndFilterGroup(array $dimensionFilters): static
    {
        $this->filters = $dimensionFilters;
        $this->filterMethod = 'and_group';

        return $this;
    }

    public function addOrFilterGroup(array $dimensionFilters): static
    {
        $this->filters = $dimensionFilters;
        $this->filterMethod = 'or_group';

        return $this;
    }

    public function orderByMetric(string $metricName, bool $desc = false): static
    {
        $this->orderByMetrics[$metricName] = [
            'name' => $metricName,
            'desc' => $desc,
        ];

        return $this;
    }

    public function orderByDimension(string $dimensionName, bool $desc = false): static
    {
        $this->orderByDimensions[$dimensionName] = [
            'name' => $dimensionName,
            'desc' => $desc,
        ];

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    protected function buildDimensionFilters()
    {
        if (empty($this->filters)) {
            return null;
        }
        if (count($this->filters) === 1) {
            return new FilterExpression(['filter' => $this->filters[0]->getGoogleFilterType()]);
        }

        return new FilterExpression([
            $this->filterMethod => new FilterExpressionList([
                'expressions' => collect($this->filters)->map(function (DimensionFilter $filter) {
                    return new FilterExpression(['filter' => $filter->getGoogleFilterType()]);
                })->toArray(),
            ]),
        ]);
    }

    protected function buildNativeDateRange()
    {
        return [
            new DateRange([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]),
        ];
    }

    protected function buildNativeDimensions()
    {
        return collect($this->dimensions)->map(function ($dimension) {
            return new Dimension($dimension);
        })->toArray();
    }

    protected function buildNativeMetrics()
    {
        return collect($this->metrics)->map(function ($metric) {
            return new Metric($metric);
        })->toArray();
    }

    protected function buildNativeOrderBy()
    {
        $metricOrders = collect($this->orderByMetrics)->map(function ($order) {
            return (new OrderBy())->setMetric(new OrderBy\MetricOrderBy([
                'metric_name' => $order['name'],
            ]))->setDesc($order['desc']);
        })->toArray();

        $dimensionOrders = collect($this->orderByDimensions)->map(function ($order) {
            return (new OrderBy())->setDimension(new OrderBy\DimensionOrderBy([
                'dimension_name' => $order['name'],
            ]))->setDesc($order['desc']);
        })->toArray();

        return [...$metricOrders, ...$dimensionOrders];
    }

    public function toGoogleObject()
    {
        $configuration = [
            'dateRanges' => $this->buildNativeDateRange(),
            'dimensions' => $this->buildNativeDimensions(),
            'metrics' => $this->buildNativeMetrics(),
            'dimensionFilter' => $this->buildDimensionFilters(),
        ];

        if (! empty($this->orderByMetrics)) {
            $configuration['orderBys'] = $this->buildNativeOrderBy();
        }

        if ($this->limit !== -1) {
            $configuration['limit'] = $this->limit;
        }

        if ($this->offset !== -1) {
            $configuration['offset'] = $this->offset;
        }

        return $configuration;
    }
}
