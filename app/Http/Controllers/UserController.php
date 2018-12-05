<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = [
            'Joel',
            'Ellie',
            'Tess',
            'Jessi'
        ];

        return view('users', [
            'users' => $users
        ]);
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
