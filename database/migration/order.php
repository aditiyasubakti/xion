<?php

use Core\Schema;

return function (Schema $schema) {

    $schema->create("order", function ($table) {
        $table->id();

        $table->integer("customer_id");
        $table->integer("users_id");
        $table->foreign("customer_id", "id", "customer");
        $table->string("produk");
        $table->integer("harga");
        $table->timestamps();
    });

};
