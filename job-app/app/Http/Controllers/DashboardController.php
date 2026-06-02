<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(Request $request)
   {
        $query = JobVacancy::query();
         // Add search functionality
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
            $query->orWhere('location', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
            $query->orWhereHas('company', function($query) use ($searchTerm) {
               $query->where('name', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
            });
        }

        // Add filter functionality
      //   if ($request->has('filter')) {
           
      //   }

        $jobs = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();
        return view('dashboard', compact('jobs'));
   }
}
