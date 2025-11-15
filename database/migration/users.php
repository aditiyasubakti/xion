<?php

return [
    "id"=> "INT AUTO_INCREMENT PRIMARY KEY",
    "nama" => "VARCHAR(100) NOT NULL",
    "username" => "VARCHAR(100) NOT NULL",
    "email"    => "VARCHAR(255) NOT NULL",
    "password" => "VARCHAR(255) NOT NULL",
    "role"     => "VARCHAR(50) DEFAULT 'user'"
];