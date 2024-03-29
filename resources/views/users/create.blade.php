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
                    <label for="profession_id">Profesión</label>
                    <select name="profession_id" id="profession_id" class="form-control">
                        <option value="">Seleccione una profesion</option>
                        @foreach($professions as $profession)
                            <option value="{{ $profession->id }}"{{ old('profession_id') == $profession->id ? " selected" : ""}}>
                                {{ $profession->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="twitter">Twitter</label>
                    <input type="text" class="form-control" name="twitter" id="twitter" placeholder="https://twitter.com/davidccgarcia" value="{{ old('twitter') }}">
                </div>

                <h5>Habilidades</h5>

                @foreach ($skills as $skill)
                    <div class="form-check form-check-inline">
                        <input name="skills[{{ $skill->id }}]"
                               class="form-check-input"
                               type="checkbox" id="skill_{{ $skill->id }}"
                               value="{{ $skill->id }}"
                               {{ old("skills.{$skill->id}") ? 'checked' : ''}}
                        >
                        <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
                    </div>
                @endforeach

                <h5 class="mt-3">Rol</h5>
                @foreach ($roles as $role => $name)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="role"
                               id="role_{{ $role }}"
                               value="{{ $role }}"
                               {{ old('role') == $role ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="role_{{ $role }}">
                            {{ $name }}
                        </label>
                    </div>
                @endforeach

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Create user</button>
                    <a href="{{ route('users') }}" class="btn btn-link">Regresar al listado de usuarios</a>
                </div>
            </form>
        </div>
    </div>

@endsection