# PHP API Service Starter

This is a PHP API service starter based on [Google Cloud Run Quickstart](https://cloud.google.com/run/docs/quickstarts/build-and-deploy/deploy-php-service).

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Contribution](#contribution)
- [License](#license)
- [Contact](#contact)

## Getting Started

To test the service locally, run:

```sh
php -S localhost:3000 index.php
```

## Configuration

Copy `.env.example` to `.env` and provide values for the required environment variables. To enable email features, set `SENDINBLUE_API_KEY` with your Brevo (Sendinblue) API key.

## Podman

The project can also be built and run inside a container using [Podman](https://podman.io/).

### Environment setup

1. Copy the example environment file and adjust values as needed:

   ```sh
   cp .env.example .env
   # edit .env to suit your local environment
   ```

2. Podman commands can load these variables with `--env-file .env` or through `podman compose`, which reads the `.env` file automatically when a `compose.yml` or `docker-compose.yml` is present.

### Build and run

Build the container image and run it directly:

```sh
podman build -t php-api .
podman run --rm -p 8080:8080 --env-file .env php-api
```

### Using Podman Compose

If you have a `compose.yml` (or `docker-compose.yml`) file, Podman can manage the service with familiar compose commands:

```sh
podman compose up --build -d    # start containers
podman compose logs -f          # view logs
podman compose down             # stop and remove containers
```

These commands are helpful during development for rebuilding images, checking logs, and tearing down containers when finished.

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

## Installation

Clone the repository and install dependencies using Composer as shown in [Getting Started](#getting-started).

## Usage

After installation, start the development server with `php -S localhost:3000 index.php` or run the container via Podman.

## Features

- Starter structure for building PHP API services
- Support for local development and containerized deployment

## Technologies Used

- PHP
- Composer
- Podman
- PHPUnit

## Contribution

Contributions are welcome! Please open an issue or submit a pull request for improvements.

## License

This project does not yet specify a license.

## Contact

For questions or support, please open an issue on GitHub.
