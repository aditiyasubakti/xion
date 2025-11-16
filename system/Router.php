<?php

class Router
{
    private static $routes = [];
    private static $groupPrefix = '';
    private static $middlewares = [];
    private static $currentMiddlewares = [];
    private static $fallback = null;

    // ------------------------------------------------
    // NORMALISASI PATH
    // ------------------------------------------------
    private static function normalize($path)
    {
        if ($path === '' || $path === null) return '/';

        $path = '/' . ltrim($path, '/');
        return rtrim($path, '/') ?: '/';
    }

    // ------------------------------------------------
    // REGISTER ROUTES
    // ------------------------------------------------
    public static function add($method, $route, $callback)
    {
        $route = self::normalize($route);
        $fullRoute = self::normalize(self::$groupPrefix . $route);

        self::$routes[$method][$fullRoute] = [
            'callback'    => $callback,
            'middlewares' => self::$currentMiddlewares
        ];

        return new RouterRegister($method, $fullRoute);
    }

    public static function get($route, $callback)    { return self::add('GET',    $route, $callback); }
    public static function post($route, $callback)   { return self::add('POST',   $route, $callback); }
    public static function put($route, $callback)    { return self::add('PUT',    $route, $callback); }
    public static function delete($route, $callback) { return self::add('DELETE', $route, $callback); }

    // ------------------------------------------------
    // ROUTE GROUP
    // ------------------------------------------------
    public static function group($prefix, $callback)
    {
        $oldPrefix = self::$groupPrefix;
        $oldMiddle = self::$currentMiddlewares;

        self::$groupPrefix = self::normalize(self::$groupPrefix . $prefix);

        call_user_func($callback);

        self::$groupPrefix = $oldPrefix;
        self::$currentMiddlewares = $oldMiddle;
    }

    // ------------------------------------------------
    // MIDDLEWARE GROUPING
    // ------------------------------------------------
    public static function middleware($middlewares)
    {
        $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];

        $old = self::$currentMiddlewares;

        // Tambahkan middleware baru (cumulative)
        self::$currentMiddlewares = array_merge(self::$currentMiddlewares, $middlewares);

        // Anonymous class untuk middleware group
        return new class($old) {
            private $old;
            public function __construct($old)
            {
                $this->old = $old;
            }

            public function group($prefix, $callback)
            {
                Router::group($prefix, function () use ($callback) {
                    call_user_func($callback);
                    Router::restoreMiddlewares($this->old);
                });
            }
        };
    }

    public static function restoreMiddlewares($list)
    {
        self::$currentMiddlewares = $list;
    }

    public static function registerMiddleware($name, $callback)
    {
        self::$middlewares[$name] = $callback;
    }

    // ------------------------------------------------
    // FALLBACK ROUTE
    // ------------------------------------------------
    public static function fallback($callback)
    {
        self::$fallback = $callback;
    }

    // ------------------------------------------------
    // RUN ROUTER
    // ------------------------------------------------
    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $config = require __DIR__ . '/../config/app.php';
        $base = rtrim($config['base_url'], '/');
        if ($base && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }
        $path = self::normalize($path);
        if (!isset(self::$routes[$method])) {
            return self::throw404();
        }
        foreach (self::$routes[$method] as $route => $data) {
            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                foreach ($data['middlewares'] as $mw) {
                    if (isset(self::$middlewares[$mw])) {
                        $result = call_user_func(self::$middlewares[$mw]);
                        if ($result === false) return;
                    }
                }
                return self::call($data['callback'], $matches);
            }
        }
        self::throw404();
    }

    // ------------------------------------------------
    // CALLABLE HANDLER (CLOSURE ATAU CONTROLLER)
    // ------------------------------------------------
    private static function call($callback, $params)
    {
        if (is_string($callback) && strpos($callback, '@') !== false) {

            list($controller, $method) = explode('@', $callback);

            $namespace = "App\\Http\\Controllers\\";
            $fullClass = $namespace . $controller;
            $file = __DIR__ . '/../app/Http/Controllers/' . $controller . '.php';

            if (!file_exists($file)) {
                http_response_code(500);
                die("File controller tidak ditemukan: $file");
            }
            require_once $file;

            if (!class_exists($fullClass)) {
                http_response_code(500);
                die(" Class controller tidak ditemukan: $fullClass");
            }

            if (!method_exists($fullClass, $method)) {
                http_response_code(500);
                die("Method $method tidak ditemukan pada $fullClass");
            }
            $obj = new $fullClass;
            return call_user_func_array([$obj, $method], $params);
        }
        return call_user_func_array($callback, $params);
    }

    // ------------------------------------------------
    // ERROR 404
    // ------------------------------------------------
    private static function throw404()
    {
        if (self::$fallback) {
            return call_user_func(self::$fallback);
        }
        http_response_code(404);
        echo "404 Not Found";
    }
    public static function setRouteName($method, $route, $name)
    {
        self::$routes[$method][$route]['name'] = $name;
    }

}
// -----------------------------------------------------------
// HANDLE ROUTE NAME (OPTIONAL)
// -----------------------------------------------------------
class RouterRegister
{
    private $method;
    private $route;
    public function __construct($method, $route)
    {
        $this->method = $method;
        $this->route = $route;
    }
   public function name($name)
{
    Router::setRouteName($this->method, $this->route, $name);
    return $this;
}

}
