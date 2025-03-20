<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;

class BurgerController extends Controller
{
    // Afficher tous les burgers avec pagination
    public function index(Request $request)
{
    $query = Burger::query();

    // Filtres
    if ($request->filled('libelle')) {
        $query->where('name', 'like', '%' . $request->libelle . '%');
    }
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Pagination (10 burgers par page)
    $burgers = $query->paginate(10);  // Appliquer la pagination ici

    //return view('burgers.index', compact('burgers'));
    return view('client.orders', compact('burgers'));
}


    // Afficher le détail d'un burger spécifique
    public function show($id)
{
    $burger = Burger::findOrFail($id);
    return view('burgers.show', compact('burger'));
}


    // Afficher le formulaire pour ajouter un burger
    public function create()
    {
        return view('burgers.create');
    }

    // Sauvegarder un nouveau burger dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0', // Validation pour le stock
        ]);

        $burger = new Burger;
        $burger->name = $request->name;
        $burger->price = $request->price;
        $burger->description = $request->description;
        $burger->stock = $request->stock;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $burger->image = $imageName;
        }

        $burger->save();

        return redirect()->route('burgers.index')->with('success','Burger ajouté avec succès!');
    }

    // Afficher le formulaire pour modifier un burger
    public function edit($id)
    {
        $burger = Burger::findOrFail($id);
        return view('burgers.edit', compact('burger'));
    }

    // Mettre à jour un burger
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'description' => 'required|string',
        'stock' => 'required|integer|min:0',
    ]);

    $burger = Burger::findOrFail($id);
    $burger->name = $request->name;
    $burger->price = $request->price;
    $burger->description = $request->description;
    $burger->stock = $request->stock;

    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $burger->image = $imageName;
    }

    $burger->save();

    // Log pour déboguer
    \Log::info('Burger mis à jour', ['burger' => $burger]);

    return redirect()->route('burgers.index')->with('success', 'Burger mis à jour avec succès!');
}


    // Supprimer un burger
    public function destroy($id)
    {
        $burger = Burger::findOrFail($id);
        $burger->delete();

        return redirect()->route('burgers.index')->with('success', 'Burger supprimé avec succès!');
    }

    public function showOrders()
{
    // Utiliser la pagination sur la requête
    $burgers = Burger::paginate(10);  // Cela renvoie une instance paginée

    // Retourner la vue avec la variable $burgers
    return view('client.orders', compact('burgers'));
}

}
