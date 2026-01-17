<?php

namespace App\Http\Controllers;

use App\Models\AssetCost;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssetCostController extends Controller
{
    /**
     * Display a listing of costs for an asset.
     */
    public function index(Asset $asset)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $costs = $asset->costs()->with('creator')->paginate(15);
        $totalCosts = $asset->total_costs;

        return view('assets.costs.index', compact('asset', 'costs', 'totalCosts'));
    }

    /**
     * Show the form for creating a new cost.
     */
    public function create(Asset $asset)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        return view('assets.costs.create', compact('asset'));
    }

    /**
     * Store a newly created cost.
     */
    public function store(Request $request, Asset $asset)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'cost_type' => 'required|in:maintenance,repair,insurance,spare_parts,upgrade,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $validated['asset_id'] = $asset->id;
        $validated['created_by'] = Auth::id();

        // Handle document upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('asset-costs', 'public');
            $validated['document_path'] = $path;
        }

        AssetCost::create($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Costo registrado exitosamente.');
    }

    /**
     * Display the specified cost.
     */
    public function show(Asset $asset, AssetCost $cost)
    {
        // Verificar que pertenece al activo y empresa correcta
        if ($cost->asset_id !== $asset->id || $asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $cost->load('creator');

        return view('assets.costs.show', compact('asset', 'cost'));
    }

    /**
     * Show the form for editing the specified cost.
     */
    public function edit(Asset $asset, AssetCost $cost)
    {
        // Verificar que pertenece al activo y empresa correcta
        if ($cost->asset_id !== $asset->id || $asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        return view('assets.costs.edit', compact('asset', 'cost'));
    }

    /**
     * Update the specified cost.
     */
    public function update(Request $request, Asset $asset, AssetCost $cost)
    {
        // Verificar que pertenece al activo y empresa correcta
        if ($cost->asset_id !== $asset->id || $asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'cost_type' => 'required|in:maintenance,repair,insurance,spare_parts,upgrade,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($cost->document_path) {
                Storage::disk('public')->delete($cost->document_path);
            }
            
            $path = $request->file('document')->store('asset-costs', 'public');
            $validated['document_path'] = $path;
        }

        $cost->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Costo actualizado exitosamente.');
    }

    /**
     * Remove the specified cost.
     */
    public function destroy(Asset $asset, AssetCost $cost)
    {
        // Verificar que pertenece al activo y empresa correcta
        if ($cost->asset_id !== $asset->id || $asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        // Delete document if exists
        if ($cost->document_path) {
            Storage::disk('public')->delete($cost->document_path);
        }

        $cost->delete();

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Costo eliminado exitosamente.');
    }

    /**
     * Download cost document.
     */
    public function downloadDocument(Asset $asset, AssetCost $cost)
    {
        // Verificar que pertenece al activo y empresa correcta
        if ($cost->asset_id !== $asset->id || $asset->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        if (!$cost->document_path || !Storage::disk('public')->exists($cost->document_path)) {
            abort(404, 'Documento no encontrado');
        }

        return Storage::disk('public')->download($cost->document_path);
    }
}
