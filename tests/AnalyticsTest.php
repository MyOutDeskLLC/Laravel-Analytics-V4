<?php

use Myoutdeskllc\LaravelAnalyticsV4\Period;
use Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration;

it('throws when invalid metrics are requested', function () {
    $runConfiguration = new \Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runConfiguration->addMetric('modelNumber');
})->throws(\Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidMetricException::class);

it('throws when invalid dimensions are requested', function () {
    $runConfiguration = new \Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runConfiguration->addDimension('personality');
})->throws(\Myoutdeskllc\LaravelAnalyticsV4\Exceptions\InvalidDimensionException::class);

it('can return a new instance of the analytics class from the factory', function () {
    $configuration = [
        'service_account_credentials_json' => [
            // I don't know why you would do this, but if you decide to, it should not implode at least
            'type' => 'service_account',
            'private_key_id' => '',
            'private_key' => '',
            'client_email' => '',
            'client_id' => '',
            'auth_uri' => '',
            'token_uri' => '',
            'auth_provider_x509_cert_url' => '',
            'client_x509_cert_url' => '',
        ],
        'property_id' => 307406578,
        'cache' => [
            'enableCaching' => true,
            'authCacheOptions' => [
                'lifetime' => 60 * 60,
                'prefix' => 'memes',
            ],
        ],
    ];
    expect(Myoutdeskllc\LaravelAnalyticsV4\LaravelAnalyticsV4Factory::createFromConfiguration($configuration))->toBeInstanceOf(\Myoutdeskllc\LaravelAnalyticsV4\LaravelAnalyticsV4::class);
});

it('properly generates the required configuration for the underlying analytics library', function () {
    // We want to see blog performance
    $filter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $filter->setDimension('landingPage');
    $filter->contains('/blog/');

    $runReport = new Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runReport->setStartDate('2022-09-01')->setEndDate('2022-09-30');
    $runReport->addDimensions(['country', 'landingPage', 'date']);
    $runReport->addMetric('sessions');
    $runReport->addFilter($filter);
    $runReport->limit(10);
    $runReport->orderByMetric('sessions', true);

    $properGoogleConfiguration = $runReport->toGoogleObject();

    expect($properGoogleConfiguration['dateRanges'])->toBeArray();
    expect($properGoogleConfiguration['dimensions'])->toBeArray();
    expect($properGoogleConfiguration['metrics'])->toBeArray();

    expect($properGoogleConfiguration['dateRanges'][0])->toBeInstanceOf(\Google\Analytics\Data\V1beta\DateRange::class);
    expect($properGoogleConfiguration['dimensions'][0])->toBeInstanceOf(\Google\Analytics\Data\V1beta\Dimension::class);
    expect($properGoogleConfiguration['metrics'][0])->toBeInstanceOf(\Google\Analytics\Data\V1beta\Metric::class);
});

it('produces proper configuration for single dimension filter configurations', function () {
    // We want to see blog performance
    $filter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $filter->setDimension('landingPage');
    $filter->contains('/blog/');

    $runReport = new Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runReport->setStartDate('2022-09-01')->setEndDate('2022-09-30');
    $runReport->addDimensions(['country', 'landingPage', 'date']);
    $runReport->addMetric('sessions');
    $runReport->addFilter($filter);
    $runReport->limit(10);
    $runReport->orderByMetric('sessions', true);

    $properGoogleConfiguration = $runReport->toGoogleObject();

    expect($properGoogleConfiguration['dimensionFilter'])->toBeInstanceOf(\Google\Analytics\Data\V1beta\FilterExpression::class);
});

it('produces proper configuration for single metric filter configurations', function () {
    // We want to see blog performance
    $filter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\NumericFilter();
    $filter->setMetric('sessions');
    $filter->greaterThanOrEqual(500);

    $runReport = new Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runReport->setStartDate('2022-09-01')->setEndDate('2022-09-30');
    $runReport->addDimensions(['country', 'landingPage', 'date']);
    $runReport->addMetric('sessions');
    $runReport->addFilter($filter);
    $runReport->limit(10);
    $runReport->orderByMetric('sessions', true);

    $properGoogleConfiguration = $runReport->toGoogleObject();

    expect($properGoogleConfiguration['metricFilter'])->toBeInstanceOf(\Google\Analytics\Data\V1beta\FilterExpression::class);
});

it('produces proper configuration for "AND" filter group configurations', function () {
    // We want to see blog performance
    $blogFilter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $blogFilter->setDimension('landingPage');
    $blogFilter->contains('/blog/');

    $countryFilter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $countryFilter->setDimension('country');
    $countryFilter->exactlyMatches('United States');

    $runReport = new Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runReport->setStartDate('2022-09-01')->setEndDate('2022-09-30');
    $runReport->addDimensions(['country', 'landingPage', 'date']);
    $runReport->addMetric('sessions');
    $runReport->addFilter($blogFilter);
    $runReport->addFilter($countryFilter);
    $runReport->limit(10);
    $runReport->orderByMetric('sessions', true);

    $properGoogleConfiguration = $runReport->toGoogleObject();

    expect($properGoogleConfiguration['dimensionFilter'])->toBeInstanceOf(\Google\Analytics\Data\V1beta\FilterExpression::class);
    // For nested items, the dimension filter is actually kinda weird. Its a filter expression with a filter expression list in it.
    /** @var \Google\Analytics\Data\V1beta\FilterExpression $filterExpression */
    $filterExpression = $properGoogleConfiguration['dimensionFilter'];

    expect($filterExpression->getAndGroup())->not()->toBeEmpty();
    expect($filterExpression->getOrGroup())->toBeEmpty();
});

it('produces proper configuration for "OR" filter group configurations', function () {
    // We want to see blog performance
    $blogFilter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $blogFilter->setDimension('landingPage');
    $blogFilter->contains('/blog/');

    $countryFilter = new Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter();
    $countryFilter->setDimension('country');
    $countryFilter->exactlyMatches('United States');

    $runReport = new Myoutdeskllc\LaravelAnalyticsV4\RunReportConfiguration();
    $runReport->setStartDate('2022-09-01')->setEndDate('2022-09-30');
    $runReport->addDimensions(['country', 'landingPage', 'date']);
    $runReport->addMetric('sessions');
    $runReport->addOrFilterGroup([$blogFilter, $countryFilter]);
    $runReport->limit(10);
    $runReport->orderByMetric('sessions', true);

    $properGoogleConfiguration = $runReport->toGoogleObject();

    expect($properGoogleConfiguration['dimensionFilter'])->toBeInstanceOf(\Google\Analytics\Data\V1beta\FilterExpression::class);
    // For nested items, the dimension filter is actually kinda weird. Its a filter expression with a filter expression list in it.
    /** @var \Google\Analytics\Data\V1beta\FilterExpression $filterExpression */
    $filterExpression = $properGoogleConfiguration['dimensionFilter'];

    expect($filterExpression->getAndGroup())->toBeEmpty();
    expect($filterExpression->getOrGroup())->not()->toBeEmpty();
});

it('can generate orderBy from dimensions only', function () {
    $period = Period::months(1);

    $config = (new RunReportConfiguration())
        ->setDateRange($period)
        ->addDimensions(['country', 'landingPage', 'date'])
        ->addMetrics(['sessions'])
        ->orderByDimension('country', true)
        ->toGoogleObject();

    expect($config['orderBys'])->not()->toBeEmpty();
});

it('includes empty rows when requested', function () {
    $period = Period::months(1);

    $config = (new RunReportConfiguration())
        ->setDateRange($period)
        ->addDimensions(['country', 'landingPage', 'date'])
        ->addMetrics(['sessions'])
        ->includeEmptyRows()
        ->toGoogleObject();

    expect($config['keepEmptyRows'])->toBeTrue();
});

it('does not include empty rows by default', function () {
    $period = Period::months(1);

    $config = (new RunReportConfiguration())
        ->setDateRange($period)
        ->addDimensions(['country', 'landingPage', 'date'])
        ->addMetrics(['sessions'])
        ->toGoogleObject();

    expect($config)->not()->toHaveKey('keepEmptyRows');
});
