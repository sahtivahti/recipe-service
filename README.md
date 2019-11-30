# RECIPE-SERVICE

[![Actions Status](https://github.com/sahtivahti/recipe-service/workflows/CI/badge.svg)](https://github.com/sahtivahti/recipe-service/actions)

Recipe related management for Sahtivahti application

## Table of Contents

* [Table of Contents](#table-of-contents)
* [Installation](#installation)
* [Database](#database)
  * [Running migrations](#running-migrations)
* [Tests](#tests)
  * [Running unit tests](#running-unit-tests)
* [License](#license)

## Requirements

* Docker
* Docker Compose

## Installation

```
docker-compose up -d
```

## Database

Application uses `mysql` database as it's primary storage.

### Running migrations

Database schema migrations is run automatically when application instance starts (see [docker-entrypoint.sh](./docker-entrypoint.sh)). During development migrations may be run using the following command

```
./bin/console doctrine:migrations:migrate
```

## Tests

Tests are located under the [tests](./tests) directory.

### Running unit tests

**PHPUnit**

```
./bin/phpunit
```

## License

MIT
