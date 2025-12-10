<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class BackupController extends Controller
{
    public function index() {
        return view('superadmin.backups.index');
    }
}
