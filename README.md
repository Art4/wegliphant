# Wegliphant

PHP client for the [weg.li](https://www.weg.li/) API

Docs: https://www.weg.li/api

Requires: PHP ^8.3

## Usage

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

### List all districts

```php
$districts = $client->listDistricts();

var_dump($data);
// $data contains:
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
