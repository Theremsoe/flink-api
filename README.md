## About the Backend Flint API

This project was maded in Laravel with the aim of dispatch a API server that store companies and validates the market values.

Is very important that you consider that this API use standars/specifications like [JSON API Schema](https://jsonapi.org/), datetimes in [RFC3339](https://datatracker.ietf.org/doc/html/rfc3339) and [POSIX](https://es.wikipedia.org/wiki/POSIX).


### Installation in local environment

#### Requirements
 - PHP 8.0
 - PostgreSQL 13 or greather
 - POSIX

After that you clone the project, you have multiples environment modes for install and run the proyect. The modes are: **development** (default), **testing** and **production**.

You can install the project like the next example:
```bash
make install
```

Or in production mode:
```bash
make install mode=production
```

After that, you need setup the .env file for configure you postgreSQL connection.


### Running tests

In this example you can install the API server in testing mode:
```bash
make install mode=testing
```

> Note: In this installation way, you will see an **.env.testing** file. You can configure that file depending of requirements.

For run tests, only execute the next command:
```bash
php artisan test --parallel
```

If you need get more inforation about each test goal you can run the next command:
```bash
make test
```

### Deploy with docker

First, we need clean up the project with the next command:
```bash
make reinstall
```

And then, you can deploy the project like the previous scenario: in **develop**, **testing** and **production** modes, for example:
```bash
make deploy mode=testing
```

> Note: in production mode, you need setup the database connection into **.env** file.
