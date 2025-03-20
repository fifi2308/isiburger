@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Modifier un Burger</h2>
        <a href="{{ route('burgers.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <!-- Formulaire de modification du burger -->
    <form action="{{ route('burgers.update', $burger->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        @method('PUT')

        <!-- Champ nom du burger -->
        <div class="form-group mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Nom du Burger</label>
            <input type="text" name="name" id="name" class="input w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('name', $burger->name) }}" required>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Champ prix -->
        <div class="form-group mb-4">
            <label for="price" class="block text-gray-700 font-medium mb-2">Prix</label>
            <input type="number" name="price" id="price" class="input w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('price', $burger->price) }}" required>
            @error('price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Champ description -->
        <div class="form-group mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea name="description" id="description" class="input w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="4" required>{{ old('description', $burger->description) }}</textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Champ stock -->
        <div class="form-group mb-4">
            <label for="stock" class="block text-gray-700 font-medium mb-2">Stock</label>
            <input type="number" name="stock" id="stock" class="input w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('stock', $burger->stock) }}" required>
            @error('stock')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Champ image -->
        <div class="form-group mb-4">
            <label for="image" class="block text-gray-700 font-medium mb-2">Image</label>
            <input type="file" name="image" id="image" class="input w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @if($burger->image)
                <div class="mt-2">
                    <img src="{{ asset('images/' . $burger->image) }}" alt="Image du burger" style="width: 100px; height: auto; border-radius: 5px;">
                </div>
            @endif
            @error('image')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary mt-4 w-full p-3 bg-indigo-600 text-white font-semibold rounded-md shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Mettre à jour le Burger
        </button>
    </form>
</div>
@endsection
