@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Mes Factures</h2>
        @if($invoices->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                            <td>{{ number_format($invoice->amount, 2) }} €</td>
                            <td>{{ $invoice->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune facture disponible.</p>
        @endif
    </div>
@endsection
