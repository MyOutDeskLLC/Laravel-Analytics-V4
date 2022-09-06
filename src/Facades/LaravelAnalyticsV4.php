<?php

namespace Myoutdeskllc\LaravelAnalyticsV4\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Myoutdeskllc\LaravelAnalyticsV4\LaravelAnalyticsV4
 */
class LaravelAnalyticsV4 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Myoutdeskllc\LaravelAnalyticsV4\LaravelAnalyticsV4::class;
    }
}
