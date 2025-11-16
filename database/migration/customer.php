<?php

use Core\Schema;

return function (Schema $schema) {

    $schema->create("customer", function ($table) {
        $table->id();
        $table->string("name");
        $table->string("email")->unique();
        $table->string("password");
        $table->timestamps();
    });

};