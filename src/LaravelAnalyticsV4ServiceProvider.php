<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Myoutdeskllc\LaravelAnalyticsV4\Commands\LaravelAnalyticsV4Command;

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
}
