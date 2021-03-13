# alura-php-parallel

## Installation

```sh
## Create php autoloader
composer dumpautoload

## Build the docker image
docker build -t php-parallel .
```

## Running

```sh
docker run --rm -itv $(pwd):/app -w /app php-parallel php
```
