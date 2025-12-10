<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class ActivityLogController extends Controller
{
    public function index() {
        return view('superadmin.coming-soon', ['title' => 'Logs de Actividad']);
    }
    public function export() {
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
