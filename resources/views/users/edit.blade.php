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
    <form action="{{ route('users.edit', $user) }}" method="POST">
        {{ csrf_field() }}
        <label for="name">Nombre Completo</label>
        <input type="text" name="name" id="name" placeholder="Pablo Perez" value="{{ old('name', $user->name) }}">
        <br>

        <label for="email">Correo electronico</label>
        <input type="text" name="email" id="email" placeholder="pperez@example.com" value="{{ old('email', $user->email) }}">
        <br>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Minimo 6 caracteres">
        <button type="submit" class="btn btn-info">Create user</button>
    </form>
@endsection