<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

passthru(sprintf(
    'php "%s/../bin/console" doctrine:database:drop --env=test --force --quiet',
    __DIR__
));
passthru(sprintf(
    'php "%s/../bin/console" doctrine:database:create --env=test --no-interaction --quiet',
    __DIR__
));
passthru(sprintf(
    'php "%s/../bin/console" doctrine:schema:create --env=test --quiet',
    __DIR__
));

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
