<?php
function loadEnv($path = __DIR__ . "/../.env")
{
    if (!file_exists($path)) {
        echo "File .env tidak ditemukan!\n";
        return [];
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {

        if (str_starts_with(trim($line), "#")) continue;
        if (!str_contains($line, "=")) continue;
        list($key, $value) = explode("=", $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if (strtolower($value) === "true") $value = true;
        if (strtolower($value) === "false") $value = false;
        $env[$key] = $value;
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }
    return $env;
}
