<!-- system/env.php -->

<?php
function loadEnv($path = __DIR__ . "/../.env")
{
    if (!file_exists($path)) {
        echo "❌ File .env tidak ditemukan!\n";
        return [];
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];

    foreach ($lines as $line) {

        // Lewati komentar
        if (str_starts_with(trim($line), "#")) continue;

        // Cuma proses yang ada tanda '='
        if (!str_contains($line, "=")) continue;

        list($key, $value) = explode("=", $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Hapus tanda kutip jika ada
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        // Support value boolean seperti true / false
        if (strtolower($value) === "true") $value = true;
        if (strtolower($value) === "false") $value = false;

        // Simpan
        $env[$key] = $value;

        // Set ke environment PHP
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }

    return $env;
}
