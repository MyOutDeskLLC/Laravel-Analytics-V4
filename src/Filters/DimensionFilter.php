<?php

namespace Myoutdeskllc\LaravelAnalyticsV4\Filters;

use Illuminate\Support\Str;
use Myoutdeskllc\LaravelAnalyticsV4\AnalyticsDimensions;
use Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidDimensionException;

abstract class DimensionFilter
{
    public string $dimension = '';

    public function setDimension(string $dimension): static
    {
        if (! Str::contains($dimension, ':') && ! in_array($dimension, AnalyticsDimensions::getAvailableDimensions())) {
            throw new InvalidDimensionException($dimension.' is not a valid dimension or custom dimension for GA4');
        }

        $this->dimension = $dimension;

        return $this;
    }

    abstract public function getGoogleFilterType();

    abstract public function toGoogleTypes();

    abstract public function toArray();
}
