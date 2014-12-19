<?php

$dbUrl = parse_url(getenv('CLEARDB_DATABASE_URL'));

$ormConfig = new \Phprest\Service\Orm\Config(
    [
        'driver'            => 'pdo_mysql',
        'host'              => $dbUrl['host'],
        'dbname'            => substr($dbUrl['path'], 1),
        'charset'           => 'utf8',
        'user'              => $dbUrl['user'],
        'password'          => $dbUrl['pass']
    ],
    [__DIR__ . '/../../api']
);

$ormConfig->migration = new \Phprest\Service\Orm\Config\Migration(__DIR__ . '/../orm/migrations');
$ormConfig->fixture = new \Phprest\Service\Orm\Config\Fixture(__DIR__ . '/../orm/fixtures');

return $ormConfig;

