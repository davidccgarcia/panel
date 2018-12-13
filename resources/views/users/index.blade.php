@extends('layout')

@section('title', 'Users')

@section('content')
    <h1>{{ $title }}</h1>
    <hr>
    <ul>
        @forelse ($users as $user)
            <li>{{ $user->name }} ({{ $user->email }}) <a href="{{ route('users.details', ['id' => $user->id]) }}">Ver detalles</a> </li>
        @empty
            <p>No hay usuarios registrados.</p>
        @endforelse
    </ul>
@endsection