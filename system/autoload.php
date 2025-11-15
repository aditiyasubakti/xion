<?php

// Load fungsi DB & ENV (karena bukan class)
require_once __DIR__ . "/DB.php";
require_once __DIR__ . "/env.php";

spl_autoload_register(function ($class) {

    $base = __DIR__ . '/../';

    // Namespace → folder
    $class = str_replace("\\", "/", $class);

    $file = $base . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
