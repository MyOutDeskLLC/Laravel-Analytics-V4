<?php

namespace Myoutdeskllc\LaravelAnalyticsV4\Filters;

use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\BetweenFilter;
use Google\Analytics\Data\V1beta\NumericValue;

class NumericFilter extends DimensionFilter
{
    protected string $operation = 'EQUAL';

    protected mixed $expression;

    protected mixed $fromNumber;

    protected mixed $toNumber;

    public function equal($number): static
    {
        $this->operation = 'EQUAL';
        $this->expression = $number;

        return $this;
    }

    public function lessThan($number): static
    {
        $this->operation = 'LESS_THAN';
        $this->expression = $number;

        return $this;
    }

    public function lessThanOrEqual($number): static
    {
        $this->operation = 'LESS_THAN_OR_EQUAL';
        $this->expression = $number;

        return $this;
    }

    public function greaterThan($number): static
    {
        $this->operation = 'GREATER_THAN';
        $this->expression = $number;

        return $this;
    }

    public function greaterThanOrEqual($number): static
    {
        $this->operation = 'GREATER_THAN_OR_EQUAL';
        $this->expression = $number;

        return $this;
    }

    public function between($from, $to): static
    {
        $this->operation = 'between';
        $this->fromNumber = $from;
        $this->toNumber = $to;

        return $this;
    }

    private function getUnderlyingValue($number): array
    {
        if (is_string($number)) {
            return [
                'int64Value' => $number,
            ];
        }

        return [
            'doubleValue' => $number,
        ];
    }

    public function toArray(): array
    {
        if ($this->operation === 'between') {
            return [
                'fromValue' => $this->getUnderlyingValue($this->fromNumber),
                'toValue' => $this->getUnderlyingValue($this->toNumber),
            ];
        }

        return [
            'operation' => $this->operation,
            'value' => $this->getUnderlyingValue($this->expression),
        ];
    }

    public function getGoogleFilterType()
    {
        $configuration = [
            'field_name' => $this->dimension,
            'numeric_filter' => $this->toGoogleTypes(),
        ];

        return new Filter($configuration);
    }

    public function toGoogleTypes()
    {
        if ($this->operation === 'between') {
            return new BetweenFilter([
                'from_value' => new NumericValue($this->getUnderlyingValue($this->fromNumber)),
                'to_value' => new NumericValue($this->getUnderlyingValue($this->toNumber)),
            ]);
        }
        $filter = new \Google\Analytics\Data\V1beta\Filter\NumericFilter();
        $filter->setValue(new NumericValue($this->getUnderlyingValue()));

        return $filter;
    }
}
