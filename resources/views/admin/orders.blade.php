@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Liste des Commandes</h1>

        <!-- Affichage d'un message de succès ou d'erreur s'il y en a -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tableau des commandes -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du client</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->product_name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->price }} €</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            
                            <!-- Formulaire pour annuler la commande -->
                            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">Annuler</button>
                            </form>

                            <!-- Formulaire pour modifier le statut de la commande -->
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control form-control-sm">
                                    <option value="en_attente" {{ $order->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en_preparation" {{ $order->status == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                    <option value="prete" {{ $order->status == 'prete' ? 'selected' : '' }}>Prête</option>
                                    <option value="payee" {{ $order->status == 'payee' ? 'selected' : '' }}>Payée</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $orders->links() }}
    </div>
@endsection
