<?php

namespace Myoutdeskllc\LaravelAnalyticsV4\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Myoutdeskllc\LaravelAnalyticsV4\LaravelAnalyticsV4ServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAnalyticsV4ServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
