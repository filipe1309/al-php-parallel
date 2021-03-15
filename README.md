# alura-php-parallel

## Technologies

- [Docker](https://www.docker.com/)
- [PHP Parallel](https://github.com/krakjoe/parallel)

## Installation

```sh
## Create php autoloader
composer dumpautoload

## Build the docker image
docker build -t php-parallel .
```

## Running

```sh
## Up & execute docker container
docker run --rm -itv $(pwd):/app -w /app php-parallel php
```
