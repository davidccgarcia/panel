@extends('layout')

@section('title', "User #{$id}")

@section('content')
    <h1>Usuario #{{ $id }}</h1>
    Mostrando el detalle del usuario: {{ $id }}
@endsection