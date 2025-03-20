@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Détails du Burger</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $burger->name }}</h5>
            <p class="card-text">
                <strong>Description:</strong> {{ $burger->description }}
            </p>
            <p class="card-text">
                <strong>Prix:</strong> {{ number_format($burger->price, 2) }} €
            </p>
            <p class="card-text">
                <strong>Stock:</strong> {{ $burger->stock }}
            </p>
            @if($burger->image)
                <img src="{{ asset('images/' . $burger->image) }}" alt="Image du burger" style="width: 200px; height: auto; border-radius: 5px;">
            @else
                <span class="text-muted">Aucune image disponible</span>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('burgers.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>
@endsection
