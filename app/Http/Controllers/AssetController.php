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
        $perPage = auth()->user()->preferences['pagination']['items_per_page'] ?? 10;
        $assets = Asset::with(['location', 'subcategory.category'])
            ->orderBy('id', 'asc')
            ->paginate($perPage);
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
        $hasFinancialModule = auth()->user()->company->hasModule('financial_control');

        $rules = [
            'custom_id' => 'nullable|string|unique:assets,custom_id',
            'location_id' => 'required|exists:locations,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,decommissioned,maintenance',
            'municipality_plate' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_frequency_days' => 'nullable|integer|min:1',
            'minimum_quantity' => 'nullable|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
        ];

        if ($hasFinancialModule) {
            $rules['cost_center_id'] = 'nullable|exists:cost_centers,id';
            $rules['depreciation_method'] = 'required|in:none,straight_line,declining_balance,units_of_production';
            $rules['useful_life_years'] = 'nullable|integer|min:1';
            $rules['salvage_value'] = 'nullable|numeric|min:0';
            $rules['depreciation_start_date'] = 'nullable|date';
        }

        $request->validate($rules);
        
        $data = $request->all();

        // Default depreciation_method if not present
        if (!$request->has('depreciation_method')) {
            $data['depreciation_method'] = 'none';
        }
        
        // Add company_id from authenticated user
        $data['company_id'] = auth()->user()->company_id;

        // Copy purchase_price to value for backward compatibility
        if (isset($data['purchase_price'])) {
            $data['value'] = $data['purchase_price'];
        }

        if (empty($data['custom_id'])) {
            $data['custom_id'] = 'AST-' . time() . '-' . rand(1000, 9999);
        }
        
        // Handle image upload to Cloudinary
        // Handle image upload with local fallback
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $result = \App\Helpers\CloudinaryHelper::upload($file, 'assets');
            
            if ($result) {
                $data['image'] = $result['url'];
                $data['image_public_id'] = $result['public_id'];
            } else {
                // Fallback to local storage with optimization
                // Uses custom helper to resize and convert to WebP/JPG
                $data['image'] = \App\Helpers\ImageOptimizer::save($file, 'assets');
                $data['image_public_id'] = null;
            }
        }
        
        $asset = Asset::create($data);
        
        // Generate QR Code logic here (placeholder)
        // $asset->update(['qr_code' => '...']);

        return redirect()->route('assets.index')->with('success', 'Activo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset->load([
            'costs.creator', 
            'costCenter.manager', 
            'movements.fromLocation', 
            'movements.toLocation', 
            'movements.user',
            'maintenances',
            'assignments.employee',
            'assignments.user',
            'location'
        ]);
        
        // Unify all events for the timeline
        $timeline = collect();

        // Add Movements
        foreach ($asset->movements as $movement) {
            $timeline->push((object)[
                'type' => 'movement',
                'date' => $movement->moved_at,
                'title' => 'Traslado de Ubicación',
                'description' => "De " . ($movement->fromLocation->name ?? 'N/A') . " a " . $movement->toLocation->name,
                'user' => $movement->user->name,
                'icon' => 'fa-exchange-alt',
                'color' => 'indigo',
                'reason' => $movement->reason
            ]);
        }

        // Add Maintenances
        foreach ($asset->maintenances as $maintenance) {
            $timeline->push((object)[
                'type' => 'maintenance',
                'date' => $maintenance->date,
                'title' => 'Mantenimiento ' . ucfirst($maintenance->type ?? 'General'),
                'description' => $maintenance->description,
                'user' => 'Sistema',
                'icon' => 'fa-wrench',
                'color' => 'orange',
                'amount' => $maintenance->cost
            ]);
        }

        // Add Assignments
        foreach ($asset->assignments as $assignment) {
            $timeline->push((object)[
                'type' => 'assignment',
                'date' => $assignment->assigned_date,
                'title' => 'Asignación de Activo',
                'description' => "Asignado a: " . $assignment->employee->full_name,
                'user' => $assignment->user->name ?? 'Admin',
                'icon' => 'fa-user-check',
                'color' => 'green',
                'notes' => $assignment->notes
            ]);

            if ($assignment->return_date) {
                $timeline->push((object)[
                    'type' => 'return',
                    'date' => $assignment->return_date,
                    'title' => 'Devolución de Activo',
                    'description' => "Devuelto por: " . $assignment->employee->full_name,
                    'user' => 'Admin',
                    'icon' => 'fa-undo',
                    'color' => 'gray',
                    'notes' => $assignment->notes
                ]);
            }
        }

        // Add Costs
        foreach ($asset->costs as $cost) {
            $timeline->push((object)[
                'type' => 'cost',
                'date' => $cost->date,
                'title' => 'Registro de Costo',
                'description' => $cost->concept,
                'user' => $cost->creator->name ?? 'Sistema',
                'icon' => 'fa-file-invoice-dollar',
                'color' => 'emerald',
                'amount' => $cost->amount
            ]);
        }

        $timeline = $timeline->sortByDesc('date');

        $locations = \App\Models\Location::where('company_id', auth()->user()->company_id)
            ->where('id', '!=', $asset->location_id)
            ->orderBy('name')
            ->get();

        return view('assets.show', compact('asset', 'locations', 'timeline'));
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
        $hasFinancialModule = auth()->user()->company->hasModule('financial_control');

        $rules = [
            'custom_id' => 'nullable|string|unique:assets,custom_id,' . $asset->id,
            'location_id' => 'required|exists:locations,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,decommissioned,maintenance',
            'municipality_plate' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_frequency_days' => 'nullable|integer|min:1',
            'minimum_quantity' => 'nullable|integer|min:0',
            'specifications' => 'nullable|string',
            'custom_attributes' => 'nullable|array',
        ];

        if ($hasFinancialModule) {
            $rules['cost_center_id'] = 'nullable|exists:cost_centers,id';
            $rules['depreciation_method'] = 'required|in:none,straight_line,declining_balance,units_of_production';
            $rules['useful_life_years'] = 'nullable|integer|min:1';
            $rules['salvage_value'] = 'nullable|numeric|min:0';
            $rules['depreciation_start_date'] = 'nullable|date';
        }

        $request->validate($rules);
        
        $data = $request->except(['image', 'image_public_id']);

        // Default depreciation_method if not present
        if (!$request->has('depreciation_method')) {
            $data['depreciation_method'] = $asset->depreciation_method ?? 'none';
        }
        
        // Copy purchase_price to value for backward compatibility
        if ($request->has('purchase_price')) {
            $data['value'] = $request->purchase_price;
        }
        
        // Handle image upload with local fallback
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Delete old image from Cloudinary if exists
            if ($asset->image_public_id) {
                \App\Helpers\CloudinaryHelper::delete($asset->image_public_id);
            }
            
            $result = \App\Helpers\CloudinaryHelper::upload($file, 'assets');
            
            if ($result) {
                $data['image'] = $result['url'];
                $data['image_public_id'] = $result['public_id'];
            } else {
                // Fallback to local with optimization
                $data['image'] = \App\Helpers\ImageOptimizer::save($file, 'assets');
                $data['image_public_id'] = null;
            }
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
        return redirect()->route('assets.show', $asset->id)->with('success', 'Activo actualizado exitosamente.');
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

    /**
     * Delete multiple assets at once
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_assets' => 'required|array|min:1',
            'selected_assets.*' => 'exists:assets,id'
        ]);

        $count = count($request->selected_assets);
        
        // Delete selected assets
        Asset::whereIn('id', $request->selected_assets)->delete();
        
        return redirect()->route('assets.index')->with('success', "Se eliminaron exitosamente $count activo(s).");
    }

    /**
     * Handle stock withdrawal from an asset
     */
    public function withdraw(Request $request, Asset $asset)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $asset->quantity,
            'reason' => 'required|string|max:255',
        ]);

        $withdrawalQuantity = $request->quantity;
        $oldQuantity = $asset->quantity;
        
        // Update asset quantity
        $asset->update([
            'quantity' => $oldQuantity - $withdrawalQuantity
        ]);

        // Record movement
        \App\Models\AssetMovement::create([
            'asset_id' => $asset->id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id, // Same location for internal withdrawal
            'user_id' => auth()->id(),
            'reason' => 'Retiro de stock: ' . $request->reason . " (Cant: {$withdrawalQuantity})",
            'moved_at' => now(),
        ]);

        $message = "Se han retirado $withdrawalQuantity unidades de {$asset->name}.";
        
        if ($asset->isLowStock()) {
            $message .= " ATENCIÓN: El stock está por debajo del mínimo.";
        }

        return redirect()->back()->with('success', $message);
    }
}
