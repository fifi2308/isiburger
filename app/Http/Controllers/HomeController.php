<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;

class HomeController extends Controller
{
    public function index()
    {
        $burgers = Burger::all(); // Assure-toi que tu as des burgers dans ta base de données
        return view('home', compact('burgers'));
    }
}
