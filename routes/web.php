<?php

use App\Http\Controllers\BurgerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
//use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;




Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});

// Routes pour la gestion des burgers
Route::resource('burgers', BurgerController::class);
Route::delete('/burgers/{id}', [BurgerController::class, 'destroy'])->name('burgers.delete');
Route::put('/burgers/{id}', [BurgerController::class, 'update'])->name('burgers.update');
Route::get('/burgers/{id}/edit', [BurgerController::class, 'edit'])->name('burgers.edit');
Route::get('burgers/{id}', [BurgerController::class, 'show'])->name('burgers.show');

// Routes pour les commandes
Route::get('orders/create/{burger_id}', [OrderController::class, 'create'])->name('orders.create');
Route::get('orders', [OrderController::class, 'index'])->name('orders.index'); // Liste des commandes
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Voir une commande spécifique

// Routes pour le panier
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add/{burger}', [CartController::class, 'add'])->name('cart.add');
Route::post('cart/remove/{burger}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Routes pour le catalogue et les factures (sans authentification)
Route::get('/catalog', [CatalogController::class, 'index'])->name('client.catalog');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('client.invoices');

// Routes pour les produits (admin)
Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');
Route::get('/admin/stats', [StatsController::class, 'index'])->name('admin.stats');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

// Routes pour les statistiques
Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
Route::post('/order/place', [OrderController::class, 'place'])->name('order.place');
Route::get('/panier', [CartController::class, 'view'])->name('cart.view');

Route::get('/panier', [CartController::class, 'view'])->name('cart.view'); // Affichage du panier
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add'); // Ajouter un burger
Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove'); // Supprimer un burger
Route::post('/panier/commande', [CartController::class, 'checkout'])->name('cart.checkout'); // Passer la commande

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/create-admin', [AdminController::class, 'createAdmin']);




Route::middleware(['auth:sanctum', 'role:admin'])->get('admin-dashboard', function () {
    return response()->json(['message' => 'Accès administrateur']);
});

Route::middleware(['auth:sanctum', 'role:client'])->get('client-dashboard', function () {
    return response()->json(['message' => 'Accès client']);
});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'showLogout'])->name('logout');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Routes pour l'admin
    Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
    Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
    Route::get('/admin/payments', [AdminController::class, 'showPayments'])->name('admin.payments');
    Route::get('/admin/statistics', [AdminController::class, 'showStatistics'])->name('admin.statistics');
});
Route::middleware(['auth', 'role:client'])->group(function () {
    // Routes pour le client
    Route::get('/client/catalogue', [ClientController::class, 'showCatalogue'])->name('client.catalogue');
    Route::get('/client/orders', [ClientController::class, 'showOrders'])->name('client.orders');
    Route::get('/client/invoices', [ClientController::class, 'showInvoices'])->name('client.invoices');
});


Route::middleware(['auth'])->group(function () {
    // Tableau de bord de l'administrateur
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Tableau de bord du client
    Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Routes pour les utilisateurs connectés
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Routes pour les administrateurs
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'client'])->group(function () {
    // Routes pour les clients
    Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
});


Route::prefix('admin')->middleware('auth')->name('admin.')->group(function() {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('burgers', BurgerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('statistics', StatisticController::class);
    Route::resource('users', UserController::class);
});

Route::prefix('client')->middleware('auth')->name('client.')->group(function() {
    Route::get('dashboard', [ClientController::class, 'index'])->name('dashboard');
    Route::resource('orders', ClientOrderController::class);
    Route::resource('invoices', ClientInvoiceController::class);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('auth');

Route::get('/admin/orders', [AdminController::class, 'orders']);




Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/cancel', [AdminController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    // Ajoutez d'autres routes administratives ici
});

Route::get('/client/orders', [ClientController::class, 'orders'])->name('client.orders');
Route::get('/client/orders', [BurgerController::class, 'showOrders'])->name('client.orders');
Route::get('/cart', [CartController::class, 'index'])->name('cart.view');
// routes/web.php

Route::post('/cart/{burger}/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/{burger}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');


Route::middleware('auth')->group(function() {
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show'); // Afficher le panier
    Route::post('/cart/{burgerId}/add', [CartController::class, 'addToCart'])->name('cart.add'); // Ajouter au panier
});

Route::get('/cart', [CartController::class, 'showCart'])->name('cart.view');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.index');



// Définir la route pour afficher la liste des factures
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

// Route pour afficher la liste des factures
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

// Route pour envoyer la facture en PDF
Route::post('/invoices/{invoice}/send-pdf', [InvoiceController::class, 'sendPdf'])->name('invoices.send_pdf');

// Route pour notifier le gestionnaire
Route::post('/invoices/{invoice}/notify-manager', [InvoiceController::class, 'notifyManager'])->name('invoices.notify_manager');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

Route::get('/cart', [CartController::class, 'showCart'])->name('cart.view');
Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->name('cart.checkout');

// Route pour afficher le panier (index)
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.index');

// Route pour ajouter un burger au panier
Route::post('/cart/{burgerId}/add', [CartController::class, 'addToCart'])->name('cart.add');

// Route pour valider la commande
Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->name('cart.checkout');
Route::get('/cart/add/{burgerId}', [CartController::class, 'addToCart'])->name('cart.add');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/client/invoices', [ClientController::class, 'showInvoices'])->name('client.invoices');
Route::post('/cart/add/{burger}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');

Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'validateOrder'])->middleware('auth')->name('cart.checkout');

Route::get('/client/invoices', [ClientController::class, 'showInvoices'])->name('client.invoices');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
   


// Ajouter au panier (accessible uniquement aux utilisateurs connectés)
Route::post('/add-to-cart/{burgerId}', [CatalogController::class, 'addToCart'])
    ->name('add_to_cart')
    ->middleware('auth'); // Ajout du middleware pour forcer la connexions


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/payments', [PaymentController::class, 'index']);


Route::get('/commande/{id}', [OrderController::class, 'show'])->name('emails.order_confirmation');

Route::post('/cart/remove/{id}', 'CartController@remove')->name('cart.remove');
Route::post('/place-order', [CartController::class, 'placeOrder'])->name('cart.placeOrder');
Route::get('/order/success', function () {
    return view('order.success');
})->name('order.success');

Route::post('/checkout', [CartController::class, 'placeOrder'])->name('cart.checkout');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');



Route::get('/clientdashboard/{id}', function ($id) {
    return view('clientdashboard', ['id' => $id]);
})->name('clientdashboard')->middleware('auth');

Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');

// Route pour afficher le checkout
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Route pour valider la commande (confirmer la commande et envoyer l'email)
Route::post('/checkout/validate', [CartController::class, 'validateOrder'])->name('cart.validate');
