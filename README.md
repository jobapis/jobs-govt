# Government Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-govt.svg?style=flat-square)](https://github.com/jobapis/jobs-govt/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-govt/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-govt)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-govt.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-govt/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-govt.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-govt)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-govt.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-govt)

This package provides [Government Jobs API](http://search.digitalgov.gov/developer/jobs.html)
support for the [Jobs Common Project](https://github.com/jobapis/jobs-common).

## Installation

To install, use composer:

```
composer require jobapis/jobs-govt
```

## Usage

Usage is the same as JobApis' Jobs Client, using `\JobApis\Jobs\Client\Provider\Govt`
as the provider.

```php
$client = new JobApis\Jobs\Client\Provider\Govt();

// Search for 200 job listings for 'project manager' in Chicago, IL
$jobs = $client
    // API parameters
    ->setQuery()                    // Attempts to extract as much "signal" as possible from the input text. Handles word variants, so a search on "nursing jobs" will find a job titled "nurse practitioner" and "RN." When parts of the query parameter are used to search against the position title, the results are ordered by relevance. When no query parameter is specified, they are ordered by date with the most recent listed first.
    ->setOrganizationIds()          // A comma-separated string specifying which federal, state, or local agencies to use as a filter.
    ->setHl()                       // No highlighting is included by default. Use 'hl=1' to highlight terms in the position title that match terms in the user's search.
    ->setSize()                     // Specifies how many results are returned (up to 100 at a time).
    ->setFrom()                     // Specifies the starting record.
    ->setTags()                     // A comma-separated string specifying the level of government. Current tags are federal, state, county, and city.
    ->setLatLon()                   // Comma-separated pair denoting the position of the searcher looking for a job. For example, 'lat_lon=37.783333,-122.416667' is the value for San Francisco, CA.
    // Extra parameters
    ->setKeyword('project manager') // See "setQuery()" method above
    ->setCount(100)                 // See "setSize()" method above
    ->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

### Location Queries

Because this API does not support location-based queries, you will need to add the location
to your setKeyword() method call. For example:

```
$jobs = $client->setKeyword('project manager in chicago, il')->getJobs();
```

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobapis/jobs-govt/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Steven Maguire](https://github.com/stevenmaguire)
- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-govt/contributors)

## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-govt/blob/master/LICENSE) for more information.
