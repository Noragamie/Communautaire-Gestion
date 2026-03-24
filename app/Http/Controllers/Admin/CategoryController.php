<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('profiles')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.unique' => 'Cette catégorie existe déjà.',
        ]);

        try {
            Category::create(['name' => $request->name, 'description' => $request->description]);
            return back()->with('success', 'Catégorie créée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de la catégorie.']);
        }
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name,'.$category->id]);
        try {
            $category->update($request->only('name','description'));
            return back()->with('success', 'Catégorie mise à jour.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Category $category)
    {
        if ($category->profiles()->exists()) {
            return back()->withErrors(['error' => 'Impossible de supprimer une catégorie contenant des profils.']);
        }
        try {
            $category->delete();
            return back()->with('success', 'Catégorie supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
