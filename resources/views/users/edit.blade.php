@extends('layout')

@section('title', "Edit user #{$user->id}")

@section('content')
    <h1>Editar usuarios</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Por favor corrige los siguientes errores: </p>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('users.update', $user) }}" method="POST">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <label for="name">Nombre Completo</label>
        <input type="text" name="name" id="name" placeholder="Pablo Perez" value="{{ old('name', $user->name) }}">
        <br>

        <label for="email">Correo electronico</label>
        <input type="text" name="email" id="email" placeholder="pperez@example.com" value="{{ old('email', $user->email) }}">
        <br>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Minimo 6 caracteres">

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" class="form-control" cols="30" rows="4">{{ old('bio', $user->profile->bio) }}</textarea>
        </div>

        <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" class="form-control" name="twitter" id="twitter" placeholder="https://twitter.com/davidccgarcia" value="{{ old('twitter', $user->profile->twitter) }}">
        </div>
        <button type="submit" class="btn btn-info">Create user</button>
    </form>
@endsection