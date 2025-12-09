<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\Employee;
use Illuminate\Http\Request;

class AssetAssignmentController extends Controller
{
    public function create(Asset $asset)
    {
        if ($asset->isAssigned()) {
            return redirect()->route('assets.show', $asset->id)
                ->with('error', 'Este activo ya está asignado.');
        }

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('assignments.create', compact('asset', 'employees'));
    }

    public function store(Request $request, Asset $asset)
    {
        if ($asset->isAssigned()) {
            return back()->with('error', 'Este activo ya está asignado.');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:assigned_date',
            'notes' => 'nullable|string',
        ]);

        $assignment = AssetAssignment::create([
            'asset_id' => $asset->id,
            'employee_id' => $request->employee_id,
            'user_id' => auth()->id(),
            'assigned_date' => $request->assigned_date,
            'expected_return_date' => $request->expected_return_date,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        // Enviar notificación
        auth()->user()->notify(new \App\Notifications\AssetAssignedNotification($assignment));

        return redirect()->route('assets.show', $asset->id)
            ->with('success', 'Activo asignado exitosamente.');
    }

    public function returnAsset(Request $request, AssetAssignment $assignment)
    {
        if ($assignment->status !== 'active') {
            return back()->with('error', 'Esta asignación ya ha sido finalizada.');
        }

        $request->validate([
            'return_date' => 'required|date|after_or_equal:assigned_date',
            'notes' => 'nullable|string',
        ]);

        $assignment->update([
            'return_date' => $request->return_date,
            'status' => 'returned',
            'notes' => $assignment->notes . "\n\nNota de devolución: " . $request->notes,
        ]);

        return redirect()->route('assets.show', $assignment->asset_id)
            ->with('success', 'Activo devuelto exitosamente.');
    }
}
