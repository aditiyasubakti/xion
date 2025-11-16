<?php





Router::get('/', function () {
    View::make('welcome', [
        'title' => 'Welcome Page'
    ]);
});
