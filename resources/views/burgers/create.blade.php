@extends('layouts.app')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
    @if(Auth::check() && Auth::user()->hasRole('admin'))
        <div class="container mx-auto px-6 py-10">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Ajouter un Burger</h2>

            @if(session('success'))
                <div class="alert alert-success mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('burgers.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-lg max-w-xl mx-auto">
                @csrf
                <div class="form-group mb-6">
                    <label for="name" class="block text-gray-700 font-medium mb-2 text-lg">Nom du Burger</label>
                    <input type="text" name="name" class="input w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="price" class="block text-gray-700 font-medium mb-2 text-lg">Prix</label>
                    <input type="number" name="price" class="input w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('price') }}" required>
                    @error('price')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="description" class="block text-gray-700 font-medium mb-2 text-lg">Description</label>
                    <textarea name="description" class="input w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="image" class="block text-gray-700 font-medium mb-2 text-lg">Image</label>
                    <input type="file" name="image" class="input w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    @error('image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="stock" class="block text-gray-700 font-medium mb-2 text-lg">Stock</label>
                    <input type="number" name="stock" class="input w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('stock', 0) }}" required>
                    @error('stock')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-6 w-full p-4 bg-indigo-600 text-white font-semibold rounded-md shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Ajouter Burger
                </button>
            </form>
        </div>
    @else
        <div class="container mx-auto px-6 py-10">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Accès refusé</h2>
            <p class="text-center">Vous devez être un administrateur pour accéder à cette page.</p>
        </div>
    @endif
@endsection
