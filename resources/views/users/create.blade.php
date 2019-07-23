@extends('layout')

@section('title', "Create users")

@section('content')

    <div class="card">
        <h4 class="card-header">Crear usuarios</h4>

        <div class="card-body">
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
            <form action="{{ route('users.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Pablo Perez" value="{{ old('name') }}">
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electronico</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="pperez@example.com" value="{{ old('email') }}">
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Minimo 6 caracteres">
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" class="form-control" cols="30" rows="4">{{ old('bio') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="twitter">Twitter</label>
                    <input type="text" class="form-control" name="twitter" id="twitter" placeholder="https://twitter.com/davidccgarcia" value="{{ old('twitter') }}">
                </div>

                <button type="submit" class="btn btn-primary">Create user</button>
                <a href="{{ route('users') }}" class="btn btn-link">Regresar al listado de usuarios</a>
            </form>
        </div>
    </div>

@endsection