<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function impersonate($user) {
        return redirect()->route('dashboard')->with('success', 'Impersonation feature coming soon');
    }
}
