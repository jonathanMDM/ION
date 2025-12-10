<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('superadmin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $companies = \App\Models\Company::orderBy('name')->get();
        return view('superadmin.announcements.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'target_audience' => 'required|in:all,specific_company,admins_only',
            'company_id' => 'nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        Announcement::create($validated);

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio creado exitosamente');
    }

    public function edit(Announcement $announcement)
    {
        $companies = \App\Models\Company::orderBy('name')->get();
        return view('superadmin.announcements.edit', compact('announcement', 'companies'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'target_audience' => 'required|in:all,specific_company,admins_only',
            'company_id' => 'nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $announcement->update($validated);

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio actualizado exitosamente');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio eliminado exitosamente');
    }

    public function toggleActive(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        return redirect()->back()
            ->with('success', 'Estado del anuncio actualizado');
    }
}
