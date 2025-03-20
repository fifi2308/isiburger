@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Liste des Burgers</h2>

    <!-- Affichage des messages de succès -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach($burgers as $burger)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('images/' . $burger->image) }}" class="card-img-top" alt="{{ $burger->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $burger->name }}</h5>
                        <p class="card-text">{{ $burger->description }}</p>
                        <p class="card-text"><strong>Prix:</strong> {{ number_format($burger->price, 2) }} €</p>
                        <p class="card-text"><strong>Stock:</strong> {{ $burger->stock }}</p>
                        
                        @if(auth()->user()->role == 'admin')
                            <!-- Si l'utilisateur est un administrateur, il peut gérer les stocks -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('burgers.edit', $burger->id) }}" class="btn btn-warning btn-sm" title="Modifier burger">Modifier</a>
                                <form action="{{ route('burgers.destroy', $burger->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer burger">Supprimer</button>
                                </form>
                            </div>
                        @elseif(auth()->user()->role == 'client')
                            <!-- Si l'utilisateur est un client, il peut ajouter le burger au panier -->
                            <form action="{{ route('cart.add', $burger->id) }}" method="POST" aria-labelledby="ajouter-panier">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="quantity" min="1" max="{{ $burger->stock }}" value="1" class="form-control" required aria-label="Quantité de {{ $burger->name }}">
                                    <button type="submit" class="btn btn-primary" title="Ajouter au panier">Ajouter au panier</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(auth()->user()->role == 'client')
        <!-- Affichage du panier du client -->
        <div class="mt-4">
            <a href="{{ route('cart.view') }}" class="btn btn-success" title="Voir mon panier">Voir mon panier</a>
        </div>
    @endif
</div>
@endsection
