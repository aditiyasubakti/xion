<?php
require_once __DIR__ . '/Csrf.php';

function route($name)
{
    $endpoint = Router::$namedRoutes[$name] ?? null;
    if (!$endpoint) return "/";

    return "/" . $endpoint;
}
