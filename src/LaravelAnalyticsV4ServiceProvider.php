<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAnalyticsV4ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-analytics-v4')
            ->hasConfigFile();
    }

    public function registeringPackage()
    {
        $this->app->bind(LaravelAnalyticsV4Client::class, function () {
            $property = config('analytics-v4.property_id');
        });
    }
}
