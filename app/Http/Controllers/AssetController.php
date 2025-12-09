<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with(['location', 'subcategory.category'])->get();
        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::all();
        $subcategories = Subcategory::with('category')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        return view('assets.create', compact('locations', 'subcategories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'custom_id' => 'nullable|string|unique:assets,custom_id',
            'location_id' => 'required|exists:locations,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,decommissioned,maintenance',
            'municipality_plate' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_frequency_days' => 'nullable|integer|min:1',
        ]);
        
        $data = $request->all();

        if (empty($data['custom_id'])) {
            $data['custom_id'] = 'AST-' . time() . '-' . rand(1000, 9999);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets', 'public');
            $data['image'] = $imagePath;
        }
        
        $asset = Asset::create($data);
        
        // Generate QR Code logic here (placeholder)
        // $asset->update(['qr_code' => '...']);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $locations = Location::all();
        $subcategories = Subcategory::with('category')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        return view('assets.edit', compact('asset', 'locations', 'subcategories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'custom_id' => 'nullable|string|unique:assets,custom_id,' . $asset->id,
            'location_id' => 'required|exists:locations,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,decommissioned,maintenance',
            'municipality_plate' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_frequency_days' => 'nullable|integer|min:1',
        ]);
        
        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($asset->image && \Storage::disk('public')->exists($asset->image)) {
                \Storage::disk('public')->delete($asset->image);
            }
            $imagePath = $request->file('image')->store('assets', 'public');
            $data['image'] = $imagePath;
        }
        
        // Track location change
        if ($request->location_id != $asset->location_id) {
            \App\Models\AssetMovement::create([
                'asset_id' => $asset->id,
                'from_location_id' => $asset->location_id,
                'to_location_id' => $request->location_id,
                'user_id' => auth()->id(),
                'reason' => 'Cambio de ubicación mediante edición de activo',
                'moved_at' => now(),
            ]);
        }
        
        $asset->update($data);
        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Activo eliminado exitosamente.');
    }

    public function showQR(Asset $asset)
    {
        if (empty($asset->custom_id)) {
            return redirect()->route('assets.index')->with('error', 'QR no disponible: Este activo no tiene un ID único asignado.');
        }
        
        return view('assets.qr', compact('asset'));
    }
}
