<!-- system/View.php -->
<?php

class View
{
    public static function make($view, $data = [])
    {
        $config = require __DIR__ . '/../config/app.php';
        $path   = rtrim($config['views_path'], '/') . '/' . $view . '.assets.php';

        if (!file_exists($path)) {
            die("View {$view} tidak ditemukan.");
        }

        extract($data);
        require $path;
    }
}
