<?php

namespace App\Http\Controllers;

use App\{Http\Requests\CreateUserRequest, User, UserProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $title = 'Listado de usuarios';

        return view('users.index', compact('users', 'title'));
    }

    public function show(User $user)
    {
        return view('users.details', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => '',
            'bio' => 'required',
            'twitter' => ['nullable', 'url']
        ]);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }


        $user->update($data);
        $userProfile = UserProfile::where('user_id', $user->id)->first();
        $userProfile->bio = $data['bio'];
        $userProfile->twitter = $data['twitter'];
        $userProfile->save();

        return redirect('users');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users');
    }
}
