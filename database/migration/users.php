<?php

use Core\Schema;

return function (Schema $schema) {

    $schema->create("users", function ($table) {
        $table->id();
        $table->string("nama");
        $table->string("username");
        $table->string("email");
        $table->string("password");
        $table->string("role");
        $table->timestamps();
    });

};