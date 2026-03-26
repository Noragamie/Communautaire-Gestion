<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogger;
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
            $category = Category::create(['name' => $request->name, 'description' => $request->description]);
            ActivityLogger::log('category_created', 'Category', $category->id, $category->name);
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
            ActivityLogger::log('category_updated', 'Category', $category->id, $category->name);
            return back()->with('success', 'Catégorie mise à jour.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Category $category)
    {
        if ($category->profiles()->exists()) {
            return back()->withErrors(['error' => 'Cette catégorie contient des profils. Utilisez la reclassification.']);
        }
        try {
            $label = $category->name;
            $id    = $category->id;
            $category->delete();
            ActivityLogger::log('category_deleted', 'Category', $id, $label);
            return back()->with('success', 'Catégorie supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function destroyWithReclassify(Request $request, Category $category)
    {
        $request->validate([
            'target_category_id' => 'nullable|exists:categories,id',
        ]);

        try {
            // Trouver ou créer la catégorie cible
            if ($request->filled('target_category_id')) {
                $targetId = $request->target_category_id;
            } else {
                $default  = Category::firstOrCreate(
                    ['slug' => 'non-classe'],
                    ['name' => 'Non classé', 'description' => 'Profils sans catégorie définie']
                );
                $targetId = $default->id;
            }

            // Déplacer tous les profils vers la catégorie cible
            $category->profiles()->update(['category_id' => $targetId]);

            $label = $category->name;
            $id    = $category->id;
            $category->delete();
            ActivityLogger::log('category_deleted', 'Category', $id, $label);

            return back()->with('success', "Catégorie « {$label} » supprimée. Les profils ont été reclassifiés.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression avec reclassification.']);
        }
    }
}
