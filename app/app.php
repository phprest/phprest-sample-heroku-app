<?php

require_once __DIR__ . '/../vendor/autoload.php';
$paths = require __DIR__ . '/../paths.php';

return getApplication(
    getApplicationConfig('phprest', '0.1', $paths),
    $paths
);

/**
 * @param \Phprest\Config $config
 * @param array $paths
 *
 * @return \Phprest\Application
 */
function getApplication(\Phprest\Config $config, array $paths)
{
    $app = new \Phprest\Application($config);

    require_once $paths['services'];
    require_once $paths['routes'];

    return $app;
}

/**
 * @param array $paths
 *
 * @return \Phprest\Config
 */
function getApplicationConfig($vendor, $apiVersion, array $paths)
{
    $config = new \Phprest\Config($vendor, $apiVersion);
    $config->setDebug(false);

    require_once $paths['config.api_version_handler'];
    require_once $paths['config.logger'];

    return $config;
}
