<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $costCenters = CostCenter::where('company_id', Auth::user()->company_id)
            ->with(['manager', 'assets'])
            ->withCount('assets')
            ->orderBy('code')
            ->paginate(15);

        return view('cost-centers.index', compact('costCenters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managers = User::where('company_id', Auth::user()->company_id)
            ->where('role', '!=', 'user')
            ->orderBy('name')
            ->get();

        return view('cost-centers.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cost_centers,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $validated['company_id'] = Auth::user()->company_id;
        $validated['is_active'] = true;

        CostCenter::create($validated);

        return redirect()->route('cost-centers.index')
            ->with('success', 'Centro de costo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CostCenter $costCenter)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($costCenter->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $costCenter->load(['manager', 'assets.category', 'assets.location']);

        $stats = [
            'total_assets' => $costCenter->assets()->count(),
            'total_value' => $costCenter->total_asset_value,
            'total_book_value' => $costCenter->total_book_value,
            'budget_used_percentage' => $costCenter->budget > 0 
                ? ($costCenter->total_asset_value / $costCenter->budget) * 100 
                : 0,
        ];

        return view('cost-centers.show', compact('costCenter', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CostCenter $costCenter)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($costCenter->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $managers = User::where('company_id', Auth::user()->company_id)
            ->where('role', '!=', 'user')
            ->orderBy('name')
            ->get();

        return view('cost-centers.edit', compact('costCenter', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CostCenter $costCenter)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($costCenter->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:cost_centers,code,' . $costCenter->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $costCenter->update($validated);

        return redirect()->route('cost-centers.index')
            ->with('success', 'Centro de costo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CostCenter $costCenter)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($costCenter->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        // Verificar si tiene activos asignados
        if ($costCenter->assets()->count() > 0) {
            return redirect()->route('cost-centers.index')
                ->with('error', 'No se puede eliminar un centro de costo con activos asignados.');
        }

        $costCenter->delete();

        return redirect()->route('cost-centers.index')
            ->with('success', 'Centro de costo eliminado exitosamente.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(CostCenter $costCenter)
    {
        // Verificar que pertenece a la empresa del usuario
        if ($costCenter->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $costCenter->update([
            'is_active' => !$costCenter->is_active
        ]);

        $status = $costCenter->is_active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "Centro de costo {$status} exitosamente.");
    }
}
