<!DOCTYPE html>
<html>
<head>
    <title>Facture</title>
</head>
<body>
    <h1>Facture de commande</h1>
    <p><strong>ID de commande :</strong> {{ $order->id }}</p>
    <p><strong>Total :</strong> {{ $order->total }} €</p>
    <p><strong>Client :</strong> {{ $order->user->name }}</p>
    <p><strong>Adresse de livraison :</strong> {{ $order->delivery_address }}</p>
    <!-- Ajoute d'autres détails si nécessaire -->
</body>
</html>
