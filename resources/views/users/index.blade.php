@extends('layout')

@section('title', 'Users')

@section('content')
    <h1>{{ $title }}</h1>
    <hr>
    <ul>
        @forelse ($users as $user)
            <li>{{ $user }}</li>
        @empty
            <p>No hay usuarios registrados.</p>
        @endforelse
    </ul>
@endsection