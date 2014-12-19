<?php

$env = getenv('PHPREST_ENV') ? getenv('PHPREST_ENV') . '/' : '';

return [
    'app'                           => __DIR__ . '/app/app.php',
    'routes'                        => __DIR__ . '/app/routes.php',
    'services'                      => __DIR__ . '/app/services.php',
    'config.logger'                 => __DIR__ . '/app/config/' . $env . 'logger.php',
    'config.service.orm'            => __DIR__ . '/app/config/' . $env . 'orm.php',
    'config.api_version_handler'    => __DIR__ . '/app/config/' . $env . 'api_version_handler.php',
];
