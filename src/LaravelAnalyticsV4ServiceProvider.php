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
            ->hasConfigFile('analytics-v4');
    }

    public function registeringPackage()
    {
        $this->app->bind(LaravelAnalyticsV4::class, function () {
            return LaravelAnalyticsV4Factory::createFromConfiguration(config('analytics-v4'));
        });

        $this->app->alias(LaravelAnalyticsV4::class, 'laravel-analytics-v4');
    }
}
