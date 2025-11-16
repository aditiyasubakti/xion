<?php

use Core\Schema;

return function (Schema $schema) {

    $schema->create("post", function ($table) {
        $table->id();
        $table->string("nama");
        $table->string("email")->unique();
        $table->string("descrpi");
        $table->timestamps();
    });

};