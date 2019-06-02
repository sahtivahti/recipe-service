# What is this?

One day this should be an app for mastering my homebrews. At the same this is a experimental project where everything is meant to be implemented just combining existing services and APIs.

## Installation

For development, use following command

```
docker-compose up -d
```

After a while the API should be available at http://localhost:3000

## Components

### API

We'll use NodeJS + Express for the everything combining API

### Recipe management

For recipe management I'll use self hosted [OpenEats](https://github.com/open-eats/OpenEats) API. It should cover most of needs which comes to recipe management.

### Project management

TODO (maybe [Trello](https://trello.com/))

### Calendar

TODO (maybe [Google Calendar](https://calendar.google.com/calendar/))

### Time tracking

TODO (maybe [Toggl](https://toggl.com/))

### Authentication

TODO (maybe [Auth0](https://auth0.com/))

### Notifications and alerts

TODO

### Reports and exports

TODO

## Configuration

Following environment variables may be used to configure application

### PORT

Port where the actual REST will run. Defaults to `3000`.

### REDIS_HOST

Redis service host. Defaults to `redis`.
