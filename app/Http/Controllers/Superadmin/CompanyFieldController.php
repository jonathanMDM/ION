<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
class CompanyFieldController extends Controller
{
    public function index() {
        return view('superadmin.company-fields.index');
    }
}
