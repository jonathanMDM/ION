<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Asset;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with('asset')->get();
        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::all();
        return view('maintenances.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'description' => 'required|string',
            'date' => 'required|date',
            'cost' => 'required|numeric',
        ]);
        
        Maintenance::create($request->all());

        // Recalcular próxima fecha si hay frecuencia definida
        $asset = Asset::find($request->asset_id);
        if ($asset->maintenance_frequency_days) {
            $asset->update([
                'next_maintenance_date' => \Carbon\Carbon::parse($request->date)->addDays($asset->maintenance_frequency_days)
            ]);
        }

        return redirect()->route('maintenances.index')->with('success', 'Registro de mantenimiento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $assets = Asset::all();
        return view('maintenances.edit', compact('maintenance', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'description' => 'required|string',
            'date' => 'required|date',
            'cost' => 'required|numeric',
        ]);
        $maintenance->update($request->all());
        return redirect()->route('maintenances.index')->with('success', 'Registro de mantenimiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'Registro de mantenimiento eliminado exitosamente.');
    }
}
