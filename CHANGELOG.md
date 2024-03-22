# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/Art4/wegliphant/compare/0.3.0...main)

### Added

- The supported weg.li-API spec as Swagger 2.0 json format was added.

### Changed

- The endpoints for listing charges and districs and getting a district by ZIP were changed to reflect the latest API changes.

## [0.3.0 - 2024-03-07](https://github.com/Art4/wegliphant/compare/0.2.0...0.3.0)

### Added

- New method `Art4\Wegliphant\Client::authenticate()` to set your API key for authorized API requests.
- New method `Art4\Wegliphant\Client::listOwnNotices()` to list all notices for the authorized user.
- New method `Art4\Wegliphant\Client::getNoticeByToken()` to get one notice for the authorized user.
- New class `Art4\Wegliphant\Exception\UnexpectedResponseException` that will be thrown if an error happens while processing the response.

### Changed

- `Art4\Wegliphant\Client` now throws `Art4\Wegliphant\Exception\UnexpectedResponseException` if an error happens while processing responses.

## [0.2.0 - 2024-03-06](https://github.com/Art4/wegliphant/compare/0.1.0...0.2.0)

### Added

- New method `Art4\Wegliphant\Client::getDistrictByZip()` to get one district by ZIP.
- New method `Art4\Wegliphant\Client::listCharges()` to list all charges.
- Support for PHP 8.1 and PHP 8.2.

## [0.1.0 - 2024-03-05](https://github.com/Art4/wegliphant/compare/3a69d42338ea699afe87fe6f9a0cb1e9059e505d...0.1.0)

### Added

- New class `Art4\Wegliphant\Client` to make API requests.
- New method `Art4\Wegliphant\Client::listDistricts()` to list all districts.
- Unit tests with PHPUnit.
- composer.json for installation support via composer.
- Docker support for local dev environment.
