# 🐘 Wegliphant

[![Latest Version](https://img.shields.io/github/release/Art4/wegliphant.svg)](https://github.com/Art4/wegliphant/releases)
[![Software License](https://img.shields.io/badge/license-GPL3%20or%20later-brightgreen.svg)](LICENSE.md)
[![Build Status](https://github.com/Art4/wegliphant/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/Art4/wegliphant/actions)
[![codecov](https://codecov.io/gh/Art4/wegliphant/graph/badge.svg?token=8J0PBN3KJE)](https://codecov.io/gh/Art4/wegliphant)
[![Total Downloads](https://img.shields.io/packagist/dt/art4/wegliphant.svg)](https://packagist.org/packages/art4/wegliphant)

🐘 Wegliphant is a PHP client for the [weg.li](https://www.weg.li/) 🚲💨 API.

- API Docs: https://www.weg.li/api
- API Source: https://www.weg.li/apidocs.json
- API Version: https://github.com/weg-li/weg-li/tree/9247d97012486a052e0a326bec83e754ace750a6
- API Datetime: 2024-03-22T08:19:09Z

Requires: PHP ^8.1

## Usage

### Installation

You can install Wegliphant using [Composer](https://getcomposer.org/):

```bash
composer require art4/wegliphant
```

Don't forget to load the autoloader using `require 'vendor/autoload.php';`.

### Setup

Wegliphant requires a [PSR-18 HTTP client](https://packagist.org/providers/psr/http-client-implementation)
and [PSR-17 Request factory](https://packagist.org/providers/psr/http-factory-implementation) implementation.

This example uses [Guzzle](http://docs.guzzlephp.org/):

```php
$client = \Art4\Wegliphant\Client::create(
    new \GuzzleHttp\Client(),
    new \GuzzleHttp\Psr7\HttpFactory(),
);
```

Optionally, you can use `authenticate()` to set an API key. Nearly all areas of the weg.li API require an API key.
Without the API key, all requests are send without authorization.
You can find your API key [here](https://www.weg.li/user/edit).

```php
$client->authenticate($apiKey);
```

### List all own notices

```php
$notices = $client->listOwnNotices();

// $notices contains:
[
    [...],
    [
        'token' => '8843d7f92416211de9ebb963ff4ce281',
        'status' => 'shared',
        'street' => 'Musterstraße 123',
        'city' => 'Berlin',
        'zip' => '12305',
        'latitude' => 52.5170365,
        'longitude' => 13.3888599,
        'registration' => 'EX AM 713',
        'color' => 'white',
        'brand' => 'Car brand',
        'charge' => [
            'tbnr' => '141312',
            'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283).',
            'fine' => '25.0',
            'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52 BKat',
            'penalty' => null,
            'fap' => null,
            'points' => 0,
            'valid_from' => '2021-11-09T00:00:00.000+01:00',
            'valid_to' => null,
            'implementation' => null,
            'classification' => 5,
            'variant_table_id' => 741017,
            'rule_id' => 39,
            'table_id' => null,
            'required_refinements' => '00000000000000000000000000000000',
            'number_required_refinements' => 0,
            'max_fine' => '0.0',
            'created_at' => '2023-09-18T15:30:43.312+02:00',
            'updated_at' => '2023-09-18T15:30:43.312+02:00',
        ],
        'tbnr' => '141312',
        'start_date' => '2023-11-12T11:31:00.000+01:00',
        'end_date' => '2023-11-12T11:36:00.000+01:00',
        'note' => 'Some user notes',
        'photos' => [
            [
                'filename' => 'IMG_20231124_113156.jpg',
                'url' => 'https://example.com/storage/IMG_20231124_113156.jpg',
            ],
        ],
        'created_at' => '2023-11-12T11:33:29.423+01:00',
        'updated_at' => '2023-11-12T11:49:24.383+01:00',
        'sent_at' => '2023-11-12T11:49:24.378+01:00',
        'vehicle_empty' => true,
        'hazard_lights' => false,
        'expired_tuv' => false,
        'expired_eco' => false,
        'over_2_8_tons' => false,
    ],
    [...],
],
```

### Get one notice by token

```php
$notice = $client->getNoticeByToken('8843d7f92416211de9ebb963ff4ce281');

// $notice contains:
[
    'token' => '8843d7f92416211de9ebb963ff4ce281',
    'status' => 'shared',
    'street' => 'Musterstraße 123',
    'city' => 'Berlin',
    'zip' => '12305',
    'latitude' => 52.5170365,
    'longitude' => 13.3888599,
    'registration' => 'EX AM 713',
    'color' => 'white',
    'brand' => 'Car brand',
    'charge' => [
        'tbnr' => '141312',
        'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283).',
        'fine' => '25.0',
        'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52 BKat',
        'penalty' => null,
        'fap' => null,
        'points' => 0,
        'valid_from' => '2021-11-09T00:00:00.000+01:00',
        'valid_to' => null,
        'implementation' => null,
        'classification' => 5,
        'variant_table_id' => 741017,
        'rule_id' => 39,
        'table_id' => null,
        'required_refinements' => '00000000000000000000000000000000',
        'number_required_refinements' => 0,
        'max_fine' => '0.0',
        'created_at' => '2023-09-18T15:30:43.312+02:00',
        'updated_at' => '2023-09-18T15:30:43.312+02:00',
    ],
    'tbnr' => '141312',
    'start_date' => '2023-11-12T11:31:00.000+01:00',
    'end_date' => '2023-11-12T11:36:00.000+01:00',
    'note' => 'Some user notes',
    'photos' => [
        [
            'filename' => 'IMG_20231124_113156.jpg',
            'url' => 'https://example.com/storage/IMG_20231124_113156.jpg',
        ],
    ],
    'created_at' => '2023-11-12T11:33:29.423+01:00',
    'updated_at' => '2023-11-12T11:49:24.383+01:00',
    'sent_at' => '2023-11-12T11:49:24.378+01:00',
    'vehicle_empty' => true,
    'hazard_lights' => false,
    'expired_tuv' => false,
    'expired_eco' => false,
    'over_2_8_tons' => false,
],
```

### List all districts

```php
$districts = $client->listDistricts();

// $districts contains:
[
    [...],
    [
        'name' => 'Berlin',
        'zip' => '12305',
        'email' => 'mail@example.com',
        'prefixes' => [
            'B',
        ],
        'latitude' => 52.5170365,
        'longitude' => 13.3888599,
        'aliases' => null,
        'personal_email' => false,
        'created_at' => '2019-09-24T14:56:35.624+02:00',
        'updated_at' => '2020-03-06T17:53:09.034+01:00',
    ],
    [...],
],
```

### Get one district by zip

```php
$district = $client->getDistrictByZip('12305');

// $district contains:
[
    'name' => 'Berlin',
    'zip' => '12305',
    'email' => 'mail@example.com',
    'prefixes' => [
        'B',
    ],
    'latitude' => 52.5170365,
    'longitude' => 13.3888599,
    'aliases' => null,
    'personal_email' => false,
    'created_at' => '2019-09-24T14:56:35.624+02:00',
    'updated_at' => '2020-03-06T17:53:09.034+01:00',
],
```

### List all charges

```php
$charges = $client->listCharges();

// $charges contains:
[
    [...],
    [
        'tbnr' => '141313',
        'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283) und behinderten +) dadurch Andere.',
        'fine' => '40.0',
        'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 1 Abs. 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52.1 BKat; § 19 OWiG',
        'penalty' => null,
        'fap' => null,
        'points' => 0,
        'valid_from' => '2021-11-09T00:00:00.000+01:00',
        'valid_to' => null,
        'implementation' => 2,
        'classification' => 5,
        'variant_table_id' => 741017,
        'rule_id' => 39,
        'table_id' => null,
        'required_refinements' => '00000000000000000000000000000000',
        'number_required_refinements' => 1,
        'max_fine' => '0.0',
        'created_at' => '2023-09-18T15:30:43.329+02:00',
        'updated_at' => '2023-09-18T15:30:43.329+02:00',
    ],
    [...],
],
```

### List one charge by tbnr

```php
$charge = $client->getChargeByTbnr('141313');

// $charge contains:
[
    'tbnr' => '141313',
    'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283) und behinderten +) dadurch Andere.',
    'fine' => '40.0',
    'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 1 Abs. 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52.1 BKat; § 19 OWiG',
    'penalty' => null,
    'fap' => null,
    'points' => 0,
    'valid_from' => '2021-11-09T00:00:00.000+01:00',
    'valid_to' => null,
    'implementation' => 2,
    'classification' => 5,
    'variant_table_id' => 741017,
    'rule_id' => 39,
    'table_id' => null,
    'required_refinements' => '00000000000000000000000000000000',
    'number_required_refinements' => 1,
    'max_fine' => '0.0',
    'created_at' => '2023-09-18T15:30:43.329+02:00',
    'updated_at' => '2023-09-18T15:30:43.329+02:00',
],
```

## Development

You can use Docker to create the full develoment environment.

```bash
docker compose build
docker compose up -d
docker compose exec php php --version
```

This will look like this:

```bash
$ docker compose exec php php --version
PHP 8.3.3 (cli) (built: Feb 16 2024 21:02:14) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.3.3, Copyright (c) Zend Technologies
    with Zend OPcache v8.3.3, Copyright (c), by Zend Technologies
    with Xdebug v3.3.1, Copyright (c) 2002-2023, by Derick Rethans
```

### Tests

We are using [PHPUnit](https://phpunit.de), [PHPStan](https://phpstan.org/) and
[PHP-CS-Fixer](https://cs.symfony.com/) for tests, static code anaylsis and for
enforcing the code style [PER-CS2.0](https://www.php-fig.org/per/coding-style/).
You can run all checks using the composer script `test`:

```bash
docker compose exec php composer test
```

You can also run one specific check using the composer scripts `phpstan`, `phpunit` or `codestyle`:

```bash
docker compose exec php composer phpstan
docker compose exec php composer phpunit
docker compose exec php composer codestyle
```
