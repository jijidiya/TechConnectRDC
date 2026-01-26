<?php

$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    throw new Exception('.env introuvable');
}

$env = parse_ini_file($envPath);

$required = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
foreach ($required as $key) {
    if (!isset($env[$key])) {
        throw new Exception("Variable d'environnement manquante : $key");
    }
}

return [
    'host' => $env['DB_HOST'],
    'db'   => $env['DB_NAME'],
    'user' => $env['DB_USER'],
    'pass' => $env['DB_PASS'],
];



