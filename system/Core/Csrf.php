<?php
namespace Core;

class Csrf
{
    public static function generate()
    {
        if (!session_id()) session_start();

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    public static function token()
    {
        if (!session_id()) session_start();

        return $_SESSION['_csrf_token'] ?? self::generate();
    }

    public static function verify()
    {
        if (!session_id()) session_start();

        $token = $_POST['_csrf'] ?? null;
        $session = $_SESSION['_csrf_token'] ?? null;

        if (!$token || !$session || !hash_equals($session, $token)) {
            http_response_code(419);
            die("CSRF Token mismatch.");
        }

        return true;
    }
}
