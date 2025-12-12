<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::orderBy('id', 'asc')->get();
        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'address' => 'nullable|string|max:255']);
        Location::create($request->all());
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate(['name' => 'required|string|max:255', 'address' => 'nullable|string|max:255']);
        $location->update($request->all());
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        // Check if location has associated assets
        $assetCount = $location->assets()->count();
        
        if ($assetCount > 0) {
            return redirect()->route('locations.index')->with('error', "No se puede eliminar la ubicación '{$location->name}' porque tiene {$assetCount} activo(s) asociado(s).");
        }
        
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Ubicación eliminada exitosamente.');
    }

    /**
     * Delete multiple locations at once
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:locations,id'
        ]);

        // Check if any location has associated assets
        $locationsWithAssets = Location::whereIn('id', $request->selected_items)
            ->withCount('assets')
            ->get()
            ->filter(function($location) {
                return $location->assets_count > 0;
            });
        
        if ($locationsWithAssets->count() > 0) {
            $names = $locationsWithAssets->pluck('name')->implode(', ');
            return redirect()->route('locations.index')->with('error', "No se pueden eliminar las siguientes ubicaciones porque tienen activos asociados: {$names}");
        }

        $count = count($request->selected_items);
        Location::whereIn('id', $request->selected_items)->delete();
        
        return redirect()->route('locations.index')->with('success', "Se eliminaron exitosamente $count ubicación(es).");
    }
}
