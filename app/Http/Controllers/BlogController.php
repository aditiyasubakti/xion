<?php

namespace App\Http\Controllers;

class BlogController
{
    public function show($slug)
    {
        echo "Artikel: " . $slug;
    }
}
