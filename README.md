# Wegliphant

PHP client for the weg.li API

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
