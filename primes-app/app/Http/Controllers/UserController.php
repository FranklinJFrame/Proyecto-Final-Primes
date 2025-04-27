<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View   
    {
        $user = User::find (1);
        
        return view('index',[
            'user' => $user,
        ]);}
        
        
    }
