<!-- resources/views/dashboard/client.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Bienvenue, Client</h1>
    <ul>
        <li><a href="{{ route('client.catalog') }}">Voir le catalogue</a></li>
        <li><a href="{{ route('client.orders') }}">Mes commandes</a></li>
        <li><a href="{{ route('client.invoices') }}">Mes factures</a></li>
    </ul>
@endsection
