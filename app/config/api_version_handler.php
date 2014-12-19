<?php

/** @var \Phprest\Config $config */

$config->setApiVersionHandler(function ($apiVersion) {
    if ( ! in_array($apiVersion, ['0.1'])) {
        throw new Phprest\Exception\NotAcceptable(0, ['Not supported Api Version']);
    }
});
