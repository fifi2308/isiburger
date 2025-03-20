<!-- resources/views/emails/order_confirmation.blade.php -->

<h1>Confirmation de votre commande</h1>
<p>Bonjour {{ $order->client_name }},</p>
<p>Merci pour votre commande ! Voici les détails :</p>

<h3>Commande #{{ $order->id }}</h3>
<p>Email : {{ $order->client_email }}</p>
<p>Statut de la commande : {{ $order->status }}</p>

<h4>Articles commandés :</h4>
<ul>
    @foreach ($orderItems as $item)
        <li>{{ $item->burger->name }} - Quantité : {{ $item->quantity }} - Prix : €{{ number_format($item->price, 2) }}</li>
    @endforeach
</ul>

<p>Total de la commande : €{{ number_format($order->total_price, 2) }}</p>

<p>Nous vous enverrons un email lorsque votre commande sera expédiée.</p>

<p>Merci de faire affaire avec nous !</p>
