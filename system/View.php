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
        ob_start();
        require $path;
        $content = ob_get_clean();

    
        $content = str_replace(
            "@csrf",
            "<input type='hidden' name='_csrf' value='".\Core\Csrf::token()."'>",
            $content
        );

        // ================
        // 3. RETURN OUTPUT
        // ================
        echo $content;
    }
}
