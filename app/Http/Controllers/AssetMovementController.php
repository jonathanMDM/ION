<?php

namespace App\Http\Controllers;

use App\Models\AssetMovement;
use App\Models\Asset;
use App\Models\Location;
use Illuminate\Http\Request;

class AssetMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetMovement::with(['asset', 'fromLocation', 'toLocation', 'user']);

        // Filtros
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('location_id')) {
            $query->where(function($q) use ($request) {
                $q->where('from_location_id', $request->location_id)
                  ->orWhere('to_location_id', $request->location_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->where('moved_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('moved_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('moved_at', 'desc')->paginate(20);
        $assets = Asset::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('asset_movements.index', compact('movements', 'assets', 'locations'));
    }

    public function show($id)
    {
        $movement = AssetMovement::with(['asset', 'fromLocation', 'toLocation', 'user'])->findOrFail($id);
        return view('asset_movements.show', compact('movement'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'to_location_id' => 'required|exists:locations,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $request->to_location_id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'moved_at' => now(),
        ]);

        // Actualizar ubicación del activo
        $asset->update(['location_id' => $request->to_location_id]);

        return redirect()->back()
            ->with('success', 'Movimiento registrado exitosamente.');
    }
}
