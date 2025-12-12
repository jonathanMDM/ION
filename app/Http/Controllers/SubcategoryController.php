<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('id', 'desc')->get();
        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);
        Subcategory::create($request->all());
        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return view('subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);
        $subcategory->update($request->all());
        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        // Check if subcategory has associated assets
        $assetCount = $subcategory->assets()->count();
        
        if ($assetCount > 0) {
            return redirect()->route('subcategories.index')->with('error', "No se puede eliminar la subcategoría '{$subcategory->name}' porque tiene {$assetCount} activo(s) asociado(s).");
        }
        
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Subcategoría eliminada exitosamente.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:subcategories,id'
        ]);

        // Check if any subcategory has associated assets
        $subcategoriesWithAssets = Subcategory::whereIn('id', $request->selected_items)
            ->withCount('assets')
            ->get()
            ->filter(function($subcategory) {
                return $subcategory->assets_count > 0;
            });
        
        if ($subcategoriesWithAssets->count() > 0) {
            $names = $subcategoriesWithAssets->pluck('name')->implode(', ');
            return redirect()->route('subcategories.index')->with('error', "No se pueden eliminar las siguientes subcategorías porque tienen activos asociados: {$names}");
        }

        $count = count($request->selected_items);
        Subcategory::whereIn('id', $request->selected_items)->delete();
        
        return redirect()->route('subcategories.index')->with('success', "Se eliminaron exitosamente $count subcategoría(s).");
    }
}
