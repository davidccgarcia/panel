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

        return view('users.index', compact('users', 'title'));
    }

    public function show($id)
    {
        return view('users.details', compact('id'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit($id)
    {
        return view('users.edit', compact('id'));
    }
}
