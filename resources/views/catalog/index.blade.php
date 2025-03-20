@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Burgers</h2>

        <!-- Affichage du bouton Ajouter un Burger uniquement pour les admins -->
        @if(auth()->check() && auth()->user()->hasRole('admin'))
            <a href="{{ route('burgers.create') }}" class="btn btn-primary">Ajouter un Burger</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <!-- Icône du panier pour les clients -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('cart.view') }}" class="btn btn-dark position-relative">
            @if(session('cart') && count(session('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>
    </div>

    <!-- Formulaire de filtre -->
    <form method="GET" action="{{ route('burgers.index') }}" class="mb-4 d-flex justify-content-end">
        <div class="input-group">
            <input type="text" name="libelle" class="form-control" placeholder="Rechercher un burger..." value="{{ request('libelle') }}">
            <input type="number" name="min_price" class="form-control" placeholder="Prix min (€)" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="form-control" placeholder="Prix max (€)" value="{{ request('max_price') }}">
            <button type="submit" class="btn btn-success">Filtrer</button>
        </div>
    </form>

    <!-- Table des burgers -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">Nom</th>
                <th class="text-center">Image</th>
                <th class="text-center">Prix</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Description</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($burgers as $burger)
                <tr>
                    <td class="text-center">{{ $burger->name }}</td>
                    <td class="text-center">
                        @if($burger->image)
                            <img src="{{ asset('images/' . $burger->image) }}" alt="Image du burger" style="width: 100px; height: auto; border-radius: 5px;">
                        @else
                            <span class="text-muted">Aucune image</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($burger->price, 2) }} €</td>
                    <td class="text-center">
                        @if($burger->stock <= 0)
                            <span class="text-danger">Rupture de stock</span>
                        @else
                            {{ $burger->stock }}
                        @endif
                    </td>
                    <td class="text-center">{{ Str::limit($burger->description, 50, '...') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Détails du burger -->
                            <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-primary btn-sm">Détails</a>
                            
                            <!-- Ajouter au panier si le stock est suffisant -->
                            @if($burger->stock > 0)
                                <form action="{{ route('cart.add', $burger->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Ajouter au panier</button>
                                </form>
                            @else
                                <span class="btn btn-secondary btn-sm" disabled>Indisponible</span>
                            @endif

                            <!-- Afficher les boutons Modifier et Supprimer uniquement pour les admins -->
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <a href="{{ route('burgers.edit', $burger->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('burgers.destroy', $burger->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bouton Commander (affiché pour tous les utilisateurs) -->
    <div class="d-flex justify-content-center mt-4">
        <button class="btn btn-success" onclick="alert('Votre commande a été enregistrée avec succès !');">Commander</button>
    </div>

    @if($burgers instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center mt-4">
        {{ $burgers->links() }}
    </div>
@endif


</div>
@endsection
