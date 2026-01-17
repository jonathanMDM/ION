<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    public function show(Category $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        // Check if category has associated subcategories
        $subcategoryCount = $category->subcategories()->count();
        
        if ($subcategoryCount > 0) {
            return redirect()->route('categories.index')->with('error', "No se puede eliminar la categoría '{$category->name}' porque tiene {$subcategoryCount} subcategoría(s) asociada(s).");
        }
        
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:categories,id'
        ]);

        // Check if any category has associated subcategories
        $categoriesWithSubcategories = Category::whereIn('id', $request->selected_items)
            ->withCount('subcategories')
            ->get()
            ->filter(function($category) {
                return $category->subcategories_count > 0;
            });
        
        if ($categoriesWithSubcategories->count() > 0) {
            $names = $categoriesWithSubcategories->pluck('name')->implode(', ');
            return redirect()->route('categories.index')->with('error', "No se pueden eliminar las siguientes categorías porque tienen subcategorías asociadas: {$names}");
        }

        $count = count($request->selected_items);
        Category::whereIn('id', $request->selected_items)->delete();
        
        return redirect()->route('categories.index')->with('success', "Se eliminaron exitosamente $count categoría(s).");
    }
}
