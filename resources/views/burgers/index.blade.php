@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Titre et bouton Ajouter un Burger -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Liste des Burgers</h2>

        @if(auth()->check() && auth()->user()->hasRole('admin'))
            <a href="{{ route('burgers.create') }}" class="btn btn-primary btn-lg">Ajouter un Burger</a>
        @endif
    </div>

    <!-- Message de succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Icône Panier -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('cart.view') }}" class="btn btn-dark position-relative btn-lg">
            🛒 Panier
            @if(session('cart') && count(session('cart')) > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ count(session('cart')) }}
                </span>
            @else
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            @endif
        </a>
    </div>

    <!-- Formulaire de filtre -->
    <form method="GET" action="{{ route('burgers.index') }}" class="mb-4 d-flex justify-content-end gap-2">
        <div class="input-group w-auto">
            <input type="text" name="libelle" class="form-control" placeholder="Rechercher un burger..." value="{{ request('libelle') }}">
            <input type="number" name="min_price" class="form-control" placeholder="Prix min (€)" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="form-control" placeholder="Prix max (€)" value="{{ request('max_price') }}">
            <button type="submit" class="btn btn-success btn-lg">Filtrer</button>
        </div>
    </form>

    <!-- Table des burgers -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
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
                                <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-primary btn-sm">Détails</a>
                                
                                @if($burger->stock > 0)
                                    <form action="{{ route('cart.add', ['burger' => $burger->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Ajouter au panier</button>
                                    </form>
                                @else
                                    <span class="btn btn-secondary btn-sm" disabled>Indisponible</span>
                                @endif

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
    </div>

    <!-- Bouton Commander -->
    <div class="d-flex justify-content-center mt-4">
        <button class="btn btn-success btn-lg" onclick="alert('Votre commande a été enregistrée avec succès !');">Commander</button>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $burgers->links() }}
    </div>
</div>

@endsection

@section('styles')
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        
        .table-responsive {
            margin-top: 20px;
        }

        .btn-lg {
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        .alert {
            font-weight: bold;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .text-primary {
            color: #007bff !important;
        }
    </style>
@endsection
