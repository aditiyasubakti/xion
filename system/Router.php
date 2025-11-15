<!-- system/Router.php -->
<?php
class Router
{
    private static $routes = [];
    private static $groupPrefix = '';
    private static $middlewares = [];
    private static $currentMiddlewares = [];
    private static $fallback = null;

    // ------------------------------------------------
    // REGISTER ROUTES
    // ------------------------------------------------
    public static function add($method, $route, $callback)
    {
        $fullRoute = self::$groupPrefix . $route;

        self::$routes[$method][$fullRoute] = [
            'callback' => $callback,
            'middlewares' => self::$currentMiddlewares
        ];

        return new RouterRegister($method, $fullRoute);
    }

    public static function get($route, $callback)
    {
        return self::add('GET', $route, $callback);
    }

    public static function post($route, $callback)
    {
        return self::add('POST', $route, $callback);
    }

    public static function put($route, $callback)
    {
        return self::add('PUT', $route, $callback);
    }

    public static function delete($route, $callback)
    {
        return self::add('DELETE', $route, $callback);
    }

    // ------------------------------------------------
    // ROUTE GROUP
    // ------------------------------------------------
    public static function group($prefix, $callback)
    {
        $oldPrefix = self::$groupPrefix;
        $oldMiddle = self::$currentMiddlewares;

        self::$groupPrefix .= $prefix;

        call_user_func($callback);

        self::$groupPrefix = $oldPrefix;
        self::$currentMiddlewares = $oldMiddle;
    }

    // ------------------------------------------------
    // MIDDLEWARE
    // ------------------------------------------------
    public static function middleware($middlewares)
    {
        $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];

        self::$currentMiddlewares = $middlewares;
        return new class {
            public function group($prefix, $callback)
            {
                Router::group($prefix, $callback);
            }
        };
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
    // RUN ROUTING
    // ------------------------------------------------
    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $config = require __DIR__ . '/../config/app.php';
        $base = rtrim($config['base_url'], '/');

        if (strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }

        if ($path === '') $path = '/';

        if (!isset(self::$routes[$method])) {
            self::throw404();
            return;
        }

        foreach (self::$routes[$method] as $route => $data) {

            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $route);

            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {

                array_shift($matches);

                // Jalankan middleware
                foreach ($data['middlewares'] as $mw) {
                    if (isset(self::$middlewares[$mw])) {
                        $res = call_user_func(self::$middlewares[$mw]);
                        if ($res === false) return;
                    }
                }

                // Callback
                self::call($data['callback'], $matches);
                return;
            }
        }

        self::throw404();
    }

    // ------------------------------------------------
    // CALLABLE HANDLER
    // ------------------------------------------------
private static function call($callback, $params)
{
    if (is_string($callback) && strpos($callback, '@') !== false) {
        list($controller, $method) = explode('@', $callback);

        // Namespace default untuk semua controller
        $namespace = "App\\Http\\Controllers\\";
        $fullClass = $namespace . $controller;

        // require file controller
        require_once __DIR__ . '/../app/Http/Controllers/' . $controller . '.php';

        if (!class_exists($fullClass)) {
            die("❌ Controller $fullClass tidak ditemukan!");
        }

        $obj = new $fullClass;
        call_user_func_array([$obj, $method], $params);
        return;
    }

    call_user_func_array($callback, $params);
}


    private static function throw404()
    {
        if (self::$fallback) {
            call_user_func(self::$fallback);
            return;
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}


// -----------------------------------------------------------
// SUPPORT CLASS FOR NAMED ROUTES (OPTIONAL)
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
        Router::$routes[$this->method][$this->route]['name'] = $name;
        return $this;
    }
}
