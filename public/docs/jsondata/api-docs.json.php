<?php
header('Content-Type: application/json');
echo json_encode([
    "basePath" => "http://". $_SERVER['HTTP_HOST'] . "/docs/jsondata",
    "apiVersion" => "0.1",
    "swaggerVersion" => "1.2",
    "apis" => [
        [
            "path" => "/camera.{format}"
        ],
        [
            "path" => "/temperatures.{format}"
        ]
    ]
]);
