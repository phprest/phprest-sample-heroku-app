<?php

$paths = require __DIR__ . '/../paths.php';

use Phprest\Service\RequestFilter;
use Phprest\Service\Validator;
use Phprest\Service\Orm;

/** @var \Phprest\Application $app */

$app->registerService(new RequestFilter\Service(), new RequestFilter\Config());
$app->registerService(new Validator\Service(), new Validator\Config());
$app->registerService(new Orm\Service(), require_once $paths['config.service.orm']);
