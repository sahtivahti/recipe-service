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
* [Events](#events)
  * [Event Structure](#event-structure)
  * [Event Types](#event-types)
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

## Events

Events are published to `RabbitMq` fanout exchange named `sahtivahti.fanout`. Any external application may catch events from there and process them as they want.

### Event structure

Every event contains following fields:

* item - the event's main content (Recipe)
* createdAt - timestamp of event creation
* type - the event type

Example of `RecipeCreatedEvent`

```json
{
    "type":"recipe.created",
    "createdAt":"2020-01-18T08:38:20+00:00",
    "item": {
        "id":1,
        "name":"My another beer recipe",
        "author":"panomestari@sahtivahti.fi",
        "userId":"auth0|foobar",
        "createdAt":"2020-01-18T08:38:20+00:00",
        "updatedAt":"2020-01-18T08:38:20+00:00",
        "style":"IPA","batchSize":15.6,
        "hops":[],
        "fermentables":[]
    }
}
```

### Event types

You can expect following events to dispatch whenever the specified action occurs in this system:

* RecipeCreatedEvent (`recipe.created`)
* RecipeUpdatedEvent (`recipe.updated`)
* RecipeDeletedEvent (`recipe.deleted`)

## Tests

Tests are located under the [tests](./tests) directory.

### Running unit tests

**PHPUnit**

```
./bin/phpunit
```

## License

MIT
