@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">🍔 Bienvenue au restaurant ISI BURGER</h1>
    <p class="text-center text-muted">Découvrez notre sélection de délicieux burgers !</p>

    @if(session('message'))
        <div class="alert alert-info text-center">
            {{ session('message') }}
        </div>
    @endif


     <!-- Formulaire de filtre -->
     <form method="GET" action="{{ route('burgers.index') }}" class="mb-4 d-flex justify-content-end">
        <div class="input-group">
            <input type="text" name="libelle" class="form-control" placeholder="Rechercher un burger..." value="{{ request('libelle') }}">
            <input type="number" name="min_price" class="form-control" placeholder="Prix min (€)" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="form-control" placeholder="Prix max (€)" value="{{ request('max_price') }}">
            <button type="submit" class="btn btn-success">Filtrer</button>
        </div>
    </form>

    <div class="row d-flex justify-content-center align-items-center">
        @foreach($burgers as $burger)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-lg border-0 rounded-lg">
                    <!-- Affichage de l'image du burger -->
                    <div class="card-img-top">
                        @if($burger->image)
                            <img src="{{ asset('images/' . $burger->image) }}" alt="{{ $burger->name }}" class="card-img-top" style="height: 250px; object-fit: cover; border-radius: 5px;">
                        @else
                            <div class="text-muted d-flex justify-content-center align-items-center" style="height: 250px;">Aucune image</div>
                        @endif
                    </div>

                    <!-- Détails du burger -->
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $burger->name }}</h5>
                        <p class="card-text">{{ Str::limit($burger->description, 50, '...') }}</p>
                        <p class="card-text"><strong>Prix:</strong> {{ number_format($burger->price, 2) }} €</p>
                        <p class="card-text"><strong>Stock:</strong> {{ $burger->stock }}</p>
                        
                    </div>

                    <!-- Actions : Voir et Ajouter au panier -->
                    <div class="card-footer text-center">
                        <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-primary btn-sm">Voir plus</a>

                        @auth
                            <!-- Bouton Ajouter au panier seulement pour les utilisateurs connectés -->
                            <form action="{{ route('cart.add', $burger->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <a href="{{ route('login', $burger->id) }}" class="btn btn-success btn-sm">Ajouter au panier</a>
                            </form>
                        @else
                            <!-- Si l'utilisateur n'est pas connecté, afficher le bouton de connexion -->
                            <a href="{{ route('login') }}" class="btn btn-warning btn-sm">Connectez-vous pour commander</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

     

    <!-- Message si aucun burger n'est disponible -->
    @if($burgers->isEmpty())
        <p class="text-center text-muted">Aucun burger disponible pour le moment.</p>
    @endif

    @if($burgers instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center mt-4">
        {{ $burgers->links() }}
    </div>
@endif


</div>
@endsection
