<?php
return new Phalcon\Config([
    'database' => [
        'adapter' => 'sqlite',
        'dbname' => __DIR__ . '/../db/webhook.db'
    ],
    'application' => [
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
        'baseUri' => '/'
    ]
]);
