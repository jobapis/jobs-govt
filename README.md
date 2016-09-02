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

Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\GovtQuery([
    'hl' => '1'
]);
```

Or via the "set" method. All of the parameters documented in the API's documentation can be added.

```php
// Add parameters via the set() method
$query->set('query', 'engineering');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('size', '100')
    ->set('from', '200');
```

*Note: The government jobs API doesn't support adding location as a parameter, but their keyword or lat_lon parameters can be used for this purpose.* 

Then inject the query object into the provider.

```php
// Instantiating an IndeedProvider with a query object
$client = new JobApis\Jobs\Client\Provider\GovtProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

This will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

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
