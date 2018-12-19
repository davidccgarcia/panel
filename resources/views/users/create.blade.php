@extends('layout')

@section('title', "Create users")

@section('content')
    <h1>Crear usuarios</h1>

    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <p>Por favor corrige los siguientes errores: </p>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <form action="{{ route('users.store') }}" method="POST">
        {{ csrf_field() }}
        <label for="name">Nombre Completo</label>
        <input type="text" name="name" id="name" placeholder="Pablo Perez">
        @if ($errors->any)
            <p>{{ $errors->first('name') }}</p>
        @endif
        <br>

        <label for="email">Correo electronico</label>
        <input type="email" name="email" id="email" placeholder="pperez@example.com" value="{{ old('email') }}">
        <br>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Minimo 6 caracteres">
        <button type="submit" class="btn btn-info">Create user</button>
    </form>
@endsection