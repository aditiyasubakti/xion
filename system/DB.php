<?php
require_once __DIR__ . "/env.php";

function db() {
    $env = loadEnv(__DIR__ . "/../.env");
    $conn = new mysqli(
        $env['DB_HOST'] ?? '127.0.0.1',
        $env['DB_USER'] ?? 'root',
        $env['DB_PASS'] ?? '',
        $env['DB_NAME'] ?? 'ujicoba',
        $env['DB_PORT'] ?? 3306
    );

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}
