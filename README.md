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
