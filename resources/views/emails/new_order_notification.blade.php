<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle commande</title>
</head>
<body>
    <h1>Une nouvelle commande a été passée !</h1>
    <p><strong>ID de commande :</strong> {{ $order->id }}</p>
    <p><strong>Client :</strong> {{ $order->user->name }}</p>
    <p><strong>Total :</strong> {{ $order->total }} €</p>
    <p><strong>Adresse de livraison :</strong> {{ $order->delivery_address }}</p>
</body>
</html>
