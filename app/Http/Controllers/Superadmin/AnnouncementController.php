<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AnnouncementController extends Controller
{
    public function index() {
        return view('superadmin.coming-soon', ['title' => 'Anuncios']);
    }
    public function create() {
        return view('superadmin.coming-soon', ['title' => 'Crear Anuncio']);
    }
    public function store(Request $request) {
        return redirect()->route('superadmin.announcements.index');
    }
    public function edit($id) {
        return view('superadmin.coming-soon', ['title' => 'Editar Anuncio']);
    }
    public function update(Request $request, $id) {
        return redirect()->route('superadmin.announcements.index');
    }
    public function destroy($id) {
        return redirect()->route('superadmin.announcements.index');
    }
}
