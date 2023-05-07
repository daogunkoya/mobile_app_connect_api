<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function assignUserRole(){
        $user = User::find(1);
        $user->assignRole('admin');
    }
}
