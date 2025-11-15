<!-- public/index.php -->
<?php

require __DIR__ . '/../system/autoload.php';
require __DIR__ . '/../system/View.php';
require __DIR__ . '/../system/Router.php';

// load routes
require_once __DIR__ . '/../routes/web.php';

// run router
Router::run();
