<?php

namespace Myoutdeskllc\LaravelAnalyticsV4;

use Myoutdeskllc\LaravelAnalyticsV4\Filters\StringFilter;

class PrebuiltRunConfigurations
{
    /**
     * Most visited page path by screenPageViews
     */
    public static function getMostVisitedPages(Period $period, int $limit = 20): RunReportConfiguration
    {
        return (new RunReportConfiguration())
            ->setDateRange($period)
            ->addDimensions(['pagePath'])
            ->addMetric('screenPageViews')
            ->orderByMetric('screenPageViews', true)
            ->limit($limit);
    }

    /**
     * Returns the most visited pages with a given path
     *
     * @param  string  $path pass in a path such as /blog/ for everything under blog
     */
    public static function getMostVisitedPagesWithPath(Period $period, string $path, int $limit = 20): RunReportConfiguration
    {
        $pathFilter = (new StringFilter())->setDimension('pagePath')->contains($path);

        return (new RunReportConfiguration())
            ->setDateRange($period)
            ->addDimensions(['pagePath'])
            ->addMetric('screenPageViews')
            ->orderByMetric('screenPageViews', true)
            ->limit($limit)
            ->addFilter($pathFilter);
    }

    /**
     * Returns run configuration for screenPageViews, with the pageReferrer dimension
     */
    public static function getTopReferrers(Period $period, int $limit = 0): RunReportConfiguration
    {
        return (new RunReportConfiguration())
            ->setDateRange($period)
            ->addDimensions(['pageReferrer'])
            ->addMetric('screenPageViews')
            ->orderByMetric('screenPageViews', true)
            ->limit($limit);
    }
}
