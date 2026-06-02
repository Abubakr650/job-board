<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
   public function index(Request $request)
   {
        $jobApplications = JobApplication::where('userId', Auth::id())->latest()->paginate(10);
        return view('job-applications.index', compact('jobApplications'));
   }
}