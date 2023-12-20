# AaxisTest

This project was generated with [Symfony](https://symfony.com/doc/current/index.html) version 6.4.

## Install Dependency

Run `composer install` for install the dependencies and framework bundles.

## Update Database Access

Open `.env` and update your credential in below line.

## Run Below command

Run Below command for migration and load default user into database.
### Create Database
```sh
  php bin/console doctrine:database:create
```
### Load Migration
```sh
  php bin/console doctrine:migrations:migrate
```

### Load Default Data

```sh
  php bin/console doctrine:fixtures:load
```
It will create one dummy user in the database with below email and password.

| Email | Password |
| ------ | ------ |
| admin@admin.com | Admin@123 |

## REST API Document

Get api document.

```http
  GET /
```
