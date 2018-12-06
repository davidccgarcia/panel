@extends('layout')

@section('title', "Edit user #{$id}")

@section('content')
    <h1>Editar Usuario #{{ $id }}</h1>
    Editar usuario: {{ $id }}
@endsection