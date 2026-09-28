<?php
$env = [];
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [null, null]);
        if ($k) $env[$k] = $v;
    }
}
return [
    'base_url' => $env['BASE_URL'] ?? 'http://localhost:8000',
    'db_path' => $env['DB_PATH'] ?? __DIR__ . '/../data/database.sqlite',
];