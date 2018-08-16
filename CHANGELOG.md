# Changelog
All Notable changes to `jobs-govt` will be documented in this file

## 1.0.1 - 2018-08-16

### Fixed
- Base URL for the Government Jobs API changed

## 1.0.0 - 2016-09-03

### Added
- Updated package name in composer file.

### Fixed
- Test namespace.

## 1.0.0-beta - 2016-09-02

### Added
- Changed namespace and organization to `jobapis`.
- Support for v2 of jobs-common package.

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Old getters and setters.

### Security
- Nothing

## 0.6.0 - 2015-09-28

### Added
- Support for all setter methods outlined in the [Government Jobs API](http://search.digitalgov.gov/developer/jobs.html)
- Readme documentation for all supported methods

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.5.0 - 2015-08-14

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Updated to v1.0.3 of jobs-common

### Removed
- Query setters for city/state/location as the API doesn't officially support them
- getParameters and parseLocation methods

### Security
- Nothing

## 0.4.3 - 2015-07-25

### Added
- Name and title field equal to returned job title
- Using start date as posted date
- Adding city/state parsing to use setCity/setState methods

### Deprecated
- Nothing

### Fixed
- Added " in " to search string when looking for locations to correct erroneous results

### Removed
- Nothing

### Security
- Nothing

## 0.4.2 - 2015-07-04

### Added
- Support for version 1.0 release of jobs-common project

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.4.1 - 2015-06-07

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Fixing tests for guzzle v.6.0

### Removed
- Nothing

### Security
- Nothing

## 0.4.0 - 2015-05-16

### Added
- Nothing

### Deprecated
- Removed support for rate_interval_code

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.3.0 - 2015-05-08

### Added
- Support for SourceId attribute in Job in common v.0.4.0

### Deprecated
- Id attribute in Job

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.2.1 - 2015-05-03

### Added
- Adding support for multiple jobs from each returned job if multiple locations are present

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.2.0 - 2015-05-02

### Added
- Changed job data structure
- Improved test for job data transformation

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.1.0 - 2015-04-15

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
