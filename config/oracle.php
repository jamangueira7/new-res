<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST_ORALCE', '192.168.2.5'),
        'port'           => env('DB_PORT_ORALCE', '1521'),
        'database'       => env('DB_DATABASE_ORALCE', 'xe'),
        'username'       => env('DB_USERNAME_ORALCE', 'ITWV'),
        'password'       => env('DB_PASSWORD_ORALCE', '89EyARtZnC'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];
