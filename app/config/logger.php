<?php

use Phprest\Service\Logger\Config as LoggerConfig;
use Phprest\Service\Logger\Service as LoggerService;
use Monolog\Handler\StreamHandler;

$loggerHandlers[] = new StreamHandler(__DIR__ . '/../storage/log', \Monolog\Logger::DEBUG);

/** @var \Phprest\Config $config */

$config->setLoggerConfig(new LoggerConfig('phprest', $loggerHandlers));
$config->setLoggerService(new LoggerService());
