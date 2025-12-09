<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Company;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('company')->latest()->paginate(10);
        return view('superadmin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('superadmin.announcements.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'target_audience' => 'required|in:all,admins_only,specific_company',
            'company_id' => 'required_if:target_audience,specific_company|nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Announcement::create($request->all());

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio creado exitosamente.');
    }

    public function edit(Announcement $announcement)
    {
        $companies = Company::all();
        return view('superadmin.announcements.edit', compact('announcement', 'companies'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'target_audience' => 'required|in:all,admins_only,specific_company',
            'company_id' => 'required_if:target_audience,specific_company|nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $announcement->update($request->all());

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio actualizado exitosamente.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio eliminado exitosamente.');
    }

    public function toggleActive(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Estado del anuncio actualizado.');
    }
}
