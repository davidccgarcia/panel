@extends('layout')

@section('title', "Create users")

@section('content')
    <h1>Crear usuarios</h1>
    <form action="{{ route('users.store') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-info">Create user</button>
    </form>
@endsection