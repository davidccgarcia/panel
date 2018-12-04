<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function welcomeWithNickname($nickname)
    {
        return "Bienvenido {$nickname}";
    }

    public function welcomeWithName($name)
    {
        $name = ucfirst($name);

        return "Bienvenido {$name}";
    }
}
