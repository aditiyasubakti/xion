<?php

Router::get('/', function () {
    View::make('welcome', [
        'title' => 'Welcome Page'
    ]);
});

Router::get('/users', 'UserController@index');

// Parameter
Router::get('/user/{id}', function ($id) {
    echo "User ID: " . $id;
});


// Controller
Router::get('/blog/{slug}', 'BlogController@show');

// POST
Router::post('/login', 'AuthController@login');

// Group
Router::group('/admin', function () {
    Router::get('/dashboard', function () {
        echo "Admin Dashboard";
    });
});

// Middleware
Router::registerMiddleware('auth', function () {
    if (!isset($_GET['token'])) {
        echo "Unauthorized"; 
        return false;
    }
});

Router::middleware('auth')->group('/panel', function () {
    Router::get('/home', function () {
        echo "Panel Home";
    });
});

// Fallback
Router::fallback(function () {
    View::make('404');
});
