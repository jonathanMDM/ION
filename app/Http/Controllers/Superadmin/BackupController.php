<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        return view('superadmin.backups.index', compact('backups'));
    }
}
