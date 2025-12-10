<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class ActivityLogController extends Controller
{
    public function index() {
        return view('superadmin.activity-logs.index');
    }
    public function export() {
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
