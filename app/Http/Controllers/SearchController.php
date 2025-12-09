<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Supplier;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->back();
        }

        // Search in Assets
        $assets = Asset::where('name', 'LIKE', "%{$query}%")
            ->orWhere('custom_id', 'LIKE', "%{$query}%")
            ->orWhere('model', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search in Locations
        $locations = Location::where('name', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search in Categories
        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search in Employees
        $employees = Employee::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search in Suppliers
        $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        $totalResults = $assets->count() + $locations->count() + $categories->count() + $employees->count() + $suppliers->count();

        return view('search.results', compact('query', 'assets', 'locations', 'categories', 'employees', 'suppliers', 'totalResults'));
    }
}
