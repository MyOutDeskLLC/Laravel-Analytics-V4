# GA4 integration for laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/myoutdeskllc/laravel-analytics-v4.svg?style=flat-square)](https://packagist.org/packages/myoutdeskllc/laravel-analytics-v4)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/myoutdeskllc/laravel-analytics-v4/run-tests?label=tests)](https://github.com/myoutdeskllc/laravel-analytics-v4/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/myoutdeskllc/laravel-analytics-v4/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/myoutdeskllc/laravel-analytics-v4/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/myoutdeskllc/laravel-analytics-v4.svg?style=flat-square)](https://packagist.org/packages/myoutdeskllc/laravel-analytics-v4)

This package offers integration to GA4 properties with some out of the box methods. Based on the original [Spatie integration](https://github.com/spatie/laravel-analytics) for GA3.

## Installation

You can install the package via composer:

```bash
composer require myoutdeskllc/laravel-analytics-v4
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-analytics-v4-config"
```

This is the contents of the published config file:

```php
return [
    'property_id' => config('analytics_property_id'),
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'cache_lifetime_in_minutes' => 60 * 24,
    'enableCaching' => config('analytics_cache'),
    'authCache' => null,
    'authCacheOptions' => [
        'lifetime' => config('analytics_cache_lifetime'),
        'prefix' => config('analytics_cache_prefix'),
    ],
];
```

## Usage

```php
$analytics = new Myoutdeskllc\LaravelAnalyticsV4();

// Prepare a report
$reportConfig = new \Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
$reportConfig->setStartDate('2022-09-01')
    ->setEndDate('2022-09-30')
    ->addDimension('country')
    ->addDimension('landingPage')
    ->addDimension('date')
    ->addMetric('sessions');

// Add a new filter to the report to match the country against United States
$filter = new \Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
$filter->setDimension('country')->exactlyMatches('United States');
$reportConfig->addFilter($filter);

// Run the report, I convert it to an array of data like below
$analytics->convertResponseToArray()->runReport($reportConfig);
```
Yay, results:
```
  [
    "dimensions" => [
      "country" => "United States",
      "landingPage" => "/",
      "date" => "20220903",
    ],
    "metrics" => [
      "sessions" => "113",
    ],
  ],
  [
    "dimensions" => [
      "country" => "United States",
      "landingPage" => "/services/",
      "date" => "20220902",
    ],
    "metrics" => [
      "sessions" => "110",
    ],
  ],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [JL](https://github.com/WalrusSoup)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
