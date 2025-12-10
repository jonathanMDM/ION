<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AnnouncementController extends Controller
{
    public function index() {
        return view('superadmin.announcements.index');
    }
    public function create() {
        return view('superadmin.announcements.create');
    }
    public function store(Request $request) {
        return redirect()->route('superadmin.announcements.index');
    }
    public function edit($id) {
        return view('superadmin.announcements.edit');
    }
    public function update(Request $request, $id) {
        return redirect()->route('superadmin.announcements.index');
    }
    public function destroy($id) {
        return redirect()->route('superadmin.announcements.index');
    }
}
