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
        return view('superadmin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'is_active' => 'boolean',
        ]);

        Announcement::create($validated);

        return redirect()->route('superadmin.announcements.index')
            ->with('success', 'Anuncio creado exitosamente');
    }

    public function edit(Announcement $announcement)
    {
        return view('superadmin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
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
