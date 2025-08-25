# PHP API Service Starter

This is a PHP API service starter based on [Google Cloud Run Quickstart](https://cloud.google.com/run/docs/quickstarts/build-and-deploy/deploy-php-service).

## Getting Started

To test the service locally, run:

```sh
php -S localhost:3000 index.php
```

## Testing

Install Composer dependencies and run the PHPUnit test suite:

```sh
composer install
./vendor/bin/phpunit
```

The tests use mocked database connections to avoid touching production data.