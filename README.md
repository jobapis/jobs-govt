# Government Jobs Client

[![Latest Version](https://img.shields.io/github/release/JobBrander/jobs-govt.svg?style=flat-square)](https://github.com/JobBrander/jobs-govt/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/JobBrander/jobs-govt/master.svg?style=flat-square&1)](https://travis-ci.org/JobBrander/jobs-govt)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/JobBrander/jobs-govt.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-govt/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/JobBrander/jobs-govt.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-govt)
[![Total Downloads](https://img.shields.io/packagist/dt/jobbrander/jobs-govt.svg?style=flat-square)](https://packagist.org/packages/jobbrander/jobs-govt)

This package provides Government Jobs API support for the JobBrander's
[Jobs Client](https://github.com/JobBrander/jobs-common).

## Installation

To install, use composer:

```
composer require jobbrander/jobs-govt
```

## Usage

Usage is the same as Job Branders's Jobs Client, using `\JobBrander\Jobs\Client\Provider\Govt`
as the provider.

```php
$client = new JobBrander\Jobs\Client\Provider\Govt();

// Search for 200 job listings for 'project manager' in Chicago, IL
$jobs = $client->setKeyword('project manager')
    ->setCity('Chicago')
    ->setState('IL')
    ->setCount(200)
    ->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/JobBrander/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/JobBrander/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobbrander/jobs-govt/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Steven Maguire](https://github.com/stevenmaguire)
- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobbrander/jobs-govt/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobbrander/jobs-govt/blob/master/LICENSE) for more information.
