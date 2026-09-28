<?php
$config = require __DIR__ . '/config.php';

function getPDO(): PDO {
    static $pdo = null;
    global $config;
    if ($pdo === null) {
        $dbPath = $config['db_path'];
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $dsn = "sqlite:" . $dbPath;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA journal_mode = WAL;');
    }
    return $pdo;
}