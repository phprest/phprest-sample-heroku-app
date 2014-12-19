<?php

$paths = require __DIR__ . '/../paths.php';

/** @var \Phprest\Application $app */
$app = require_once $paths['app'];

$app->run();
