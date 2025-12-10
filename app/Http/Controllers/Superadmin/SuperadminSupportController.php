<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class SuperadminSupportController extends Controller
{
    public function index() {
        return view('superadmin.coming-soon', ['title' => 'Soporte']);
    }
}
