@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tableau de bord Administrateur</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Gestion des Burgers</div>
                <div class="card-body">
                    <h5 class="card-title">Ajoutez, modifiez ou supprimez des burgers</h5>
                    <a href="{{ route('admin.burgers.index') }}" class="btn btn-light">Gérer les burgers</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Gestion des Commandes</div>
                <div class="card-body">
                    <h5 class="card-title">Visualisez et gérez toutes les commandes</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Voir les commandes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Gestion des Paiements</div>
                <div class="card-body">
                    <h5 class="card-title">Consultez et gérez les paiements des clients</h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-light">Voir les paiements</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Statistiques</div>
                <div class="card-body">
                    <h5 class="card-title">Consultez les statistiques des ventes et des produits</h5>
                    <a href="{{ route('admin.statistics.index') }}" class="btn btn-light">Voir les statistiques</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Gestion des Utilisateurs</div>
                <div class="card-body">
                    <h5 class="card-title">Gérez les utilisateurs et leurs rôles</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
