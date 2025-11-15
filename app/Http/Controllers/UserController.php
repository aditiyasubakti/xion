<?php
namespace App\Http\Controllers;

use App\Models\Users;
use View;

class UserController
{
    public function index()
    {
  $users = (new Users())->all();

    View::make('about', [
        'title' => 'About Page',
        'users' => $users
    ]);

    }
}
