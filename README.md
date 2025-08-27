# PHP API Service Starter

This is a PHP API service starter based on [Google Cloud Run Quickstart](https://cloud.google.com/run/docs/quickstarts/build-and-deploy/deploy-php-service).

## Getting Started

To test the service locally, run:

```sh
php -S localhost:3000 index.php
```

## Configuration

Copy `.env.example` to `.env` and provide values for the required environment variables. To enable email features, set `SENDINBLUE_API_KEY` with your Brevo (Sendinblue) API key.

## Testing

Install Composer dependencies and run the PHPUnit test suite:

```sh
composer install
./vendor/bin/phpunit
```

If the project requires packages from private GitHub repositories, configure Composer authentication before running `composer install`:

```sh
export GITHUB_TOKEN=your_token
# or
composer config -g github-oauth.github.com your_token
```

If you cannot provide credentials, mirror or replace those private dependencies with public equivalents so `composer install` works without special access.

The tests use mocked database connections to avoid touching production data.

## Container Utilities

Use Podman to run commands inside the containers. For example, to create the `countries` table inside the `db` service:

```sh
podman compose exec -T db mysql -u mbh -p'#yuCyTJ!FI=K' mbhdb -e "CREATE TABLE countries (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL UNIQUE) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

This runs the command within the `db` container managed by Podman Compose.
