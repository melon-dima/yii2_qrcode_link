<?php

$dbDsn = getenv('DB_DSN');
if ($dbDsn === false || $dbDsn === '') {
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_DATABASE') ?: 'short_links';
    $dbDsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $dbHost, $dbPort, $dbName);
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dbDsn,
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
];
