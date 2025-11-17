<?php

require_once __DIR__ . "/DB.php";
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/error.php";
require_once __DIR__ . "/Core/helpers.php";

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    renderError("PHP Error", $errstr, $errfile, $errline);
});
set_exception_handler(function($ex) {
    renderError("Uncaught Exception", $ex->getMessage(), $ex->getFile(), $ex->getLine());
});

spl_autoload_register(function ($class) {

    $base = __DIR__ . '/../';
    $class = str_replace("\\", "/", $class);
    $file = $base . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
