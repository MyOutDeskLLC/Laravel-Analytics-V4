<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Carbon\Carbon;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Metric;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidDimensionException;
use Myoutdeskllc\LaravelAnalyticsV4\Filters\DimensionFilter;
use Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter;

class RunReportConfiguration
{
    protected string $startDate;
    protected string $endDate;
    protected array $dimensions = [];
    protected array $metrics = [];
    /** @var DimensionFilter[] */
    protected array $filters = [];
    protected string $filterMethod = 'andGroup';


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
        if(!Str::contains($dimension, ':') && !in_array($dimension, AnalyticsDimensions::getAvailableDimensions())) {
            throw new InvalidDimensionException($dimension.' is not a valid dimension or custom dimension for GA4');
        }

        $this->dimensions[] = [
            'name' => $dimension
        ];

        return $this;
    }

    public function addMetric(string $metric): static
    {
        if(!Str::contains($metric, ':') && !in_array($metric, AnalyticsMetrics::getAvailableMetrics())) {
            throw new InvalidDimensionException($metric.' is not a valid dimension or custom dimension for GA4');
        }

        $this->metrics[] = [
            'name' => $metric
        ];

        return $this;
    }

    public function addSingleFilter(DimensionFilter $filter)
    {
        $this->filters[] = $filter;
    }

    public function addAndFilterGroup(array $dimensionFilters)
    {
        $this->filters = $dimensionFilters;
        $this->filterMethod = 'andGroup';
    }

    public function addOrFilterGroup(array $dimensionFilters)
    {
        $this->filters = $dimensionFilters;
        $this->filterMethod = 'orGroup';
    }

    public function buildDimensionFilters()
    {
        if(count($this->filters) === 1) {
            return new FilterExpression(['filter' => $this->filters[0]->getGoogleFilterType()]);
        }
        if($this->filterMethod === 'andGroup') {
            return new FilterExpressionList(['expressions' => collect($this->filters)->map(function(DimensionFilter $filter) {
                return new FilterExpression(['filter' => $filter->getGoogleFilterType()]);
            })->toArray()]);
        }
    }

    public function buildNativeDateRange()
    {
        return [
            new DateRange([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ])
        ];
    }

    public function buildNativeDimensions()
    {
        return collect($this->dimensions)->map(function($dimension) {
            return new Dimension($dimension);
        })->toArray();
    }

    public function buildNativeMetrics()
    {
        return collect($this->metrics)->map(function($metric) {
            return new Metric($metric);
        })->toArray();
    }

    public function toArray()
    {
        return [
            'dateRanges' => $this->buildNativeDateRange(),
            'dimensions' => $this->buildNativeDimensions(),
            'metrics' => $this->buildNativeMetrics(),
            'dimensionFilter' => $this->buildDimensionFilters()
        ];
    }
}
