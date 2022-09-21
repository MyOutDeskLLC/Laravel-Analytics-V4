<?php

namespace Myoutdeskllc\LaravelAnalyticsV4\Exceptions;

use DateTimeInterface;
use Exception;

class InvalidPeriodException extends Exception
{
    public static function startDateCannotBeAfterEndDate(DateTimeInterface $startDate, DateTimeInterface $endDate): self
    {
        return new self("Start date `{$startDate->format('Y-m-d')}` cannot be after end date `{$endDate->format('Y-m-d')}`.");
    }
}
