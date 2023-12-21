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

For the documentation of API you need to run asset install command.
You must need to set header `X-AUTH-TOKEN` with each request. you can get this header value via login api `/createClient`
it will return `token`.  Example `X-AUTH-TOKEN : 588954ff2809fdcb6eea6979f15e712572b9`
```sh
  php bin/console assets:install
```
Then navigate to the below url
```http
  GET /
```
