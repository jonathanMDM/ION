<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class FieldConfigController extends Controller
{
    public function index() {
        return view('superadmin.fields.index');
    }
}
