<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (request()->has('empty')) {
            $users = [];
        } else {
            $users = ['Joel','Ellie','Tess','Jessi','<script>alert("Hello World");</script>'];
        }

        $title = 'List of users';

        return view('users', compact('users', 'title'));
    }

    public function show($id)
    {
        return "Mostrando el detalle del usuario: {$id}";
    }

    public function create()
    {
        return "Crear nuevo usuario";
    }

    public function edit($id)
    {
        return "Editar usuario: {$id}";
    }
}
