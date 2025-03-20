<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'ISI Burger') }}</title>

    <!-- CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Styles de base */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            background-color: #343a40;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #f8f9fa;
        }

        .cart-icon {
            position: relative;
        }

        .cart-icon i {
            font-size: 28px; /* Taille plus grande */
            color: white;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .container {
            flex: 1;
            padding: 2rem;
            margin-bottom: 60px;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-links">
            <a href="{{ route('home') }}">Accueil</a>
            <a href="{{ route('burgers.index') }}">Burgers</a>
            @if(auth()->check() && auth()->user()->hasRole('admin'))
                <a href="{{ route('burgers.create') }}">Ajouter Burger</a>
            @endif
            <a href="{{ url('/about') }}">À propos</a>
        </div>

        <!-- Icône du panier -->
        <div class="cart-icon">
            <a href="{{ route('cart.view') }}">
                <i class="fas fa-shopping-cart"></i>
                @if(session('cart') && count(session('cart')) > 0)
                <span class="badge badge-pill badge-danger">{{ count(session('cart')) }}</span>
                @endif
            </a>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 ISI Burger. Tous droits réservés.</p>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
