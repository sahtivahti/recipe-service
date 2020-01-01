# RECIPE-SERVICE

[![Actions Status](https://github.com/sahtivahti/recipe-service/workflows/CI/badge.svg)](https://github.com/sahtivahti/recipe-service/actions)
[![Actions Status](https://github.com/sahtivahti/recipe-service/workflows/Publish/badge.svg)](https://github.com/sahtivahti/recipe-service/actions)
[![codecov](https://codecov.io/gh/sahtivahti/recipe-service/branch/master/graph/badge.svg)](https://codecov.io/gh/sahtivahti/recipe-service)


Recipe related management for Sahtivahti application

## Table of Contents

* [Table of Contents](#table-of-contents)
* [Installation](#installation)
* [API documentation](#api-documentation)
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

## API documentation

Swagger API documentation is available at http://localhost:8080/doc after the application has started.

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
