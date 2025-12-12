<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('first_name')->paginate(20);
        $departments = Employee::select('department')->distinct()->whereNotNull('department')->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    public function show(Employee $employee)
    {
        // Cargar asignaciones activas (actualmente asignados)
        $activeAssignments = $employee->assignments()
            ->with(['asset.location', 'asset.subcategory.category'])
            ->where('status', 'active')
            ->orderBy('assigned_date', 'desc')
            ->get();

        // Cargar historial de asignaciones (devueltos)
        $assignmentHistory = $employee->assignments()
            ->with(['asset.location', 'asset.subcategory.category'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc')
            ->get();

        return view('employees.show', compact('employee', 'activeAssignments', 'assignmentHistory'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Employee $employee)
    {
        // Verificar si tiene activos asignados antes de eliminar
        // if ($employee->assignments()->where('status', 'active')->exists()) {
        //     return back()->with('error', 'No se puede eliminar el empleado porque tiene activos asignados.');
        // }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}
