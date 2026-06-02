<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobApplicationUpdateeRequest;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //        $query = JobApplication::query();

    public function index(Request $request)
    {

        $query = JobApplication::query()->with(['user', 'jobVacancy']);

        // Add search functionality
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            
            $query->where(function($query) use ($searchTerm) {
                $query->where('status', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%')
                ->orWhere('aiGeneratedScore', 'LIKE', addcslashes($searchTerm, '%_\\') . '%');

                $query->orWhereHas('user', function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
                });
                
                $query->orWhereHas('jobVacancy', function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
                });
                if(Auth::user()->role == 'admin'){
                    $query->orWhereHas('jobVacancy.company', function($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
                    });
                }
            });
        }
        
        if(Auth::user()->role == 'company-owner'){
            $query->WhereHas('jobVacancy.company', function($query) {
                $query->where('companyId', Auth::user()->company->id);
            });
        }

        // Archived 
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed()->orderBy('deleted_at', 'desc');
        } 
        // Active 
        else {
            $query->latest(); 
        }

        $jobApplications = $query->paginate(10)->onEachSide(1)->withQueryString();
        $archivedCount = $query->onlyTrashed()->count();
        
        return view('job-application.index', compact('jobApplications', 'archivedCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $jobApplication = JobApplication::with(['user', 'jobVacancy'])->findOrFail($id);
        $jobApplication = JobApplication::findOrFail($id);
        return view('job-application.show', compact('jobApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        return view('job-application.edit', compact('jobApplication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobApplicationUpdateeRequest $request, string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->update([
            'status' => $request->input('status'),
        ]);

        if($request->query('redirectToList') == 'false'){
            return redirect()->route('job-applications.show', $id)->with('success', 'Job application updated successfully!');
        }
        return redirect()->route('job-applications.index')->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->delete();
        return redirect()->route('job-applications.index')->with('success', 'Job application archived successfully!');
    }

     public function restore(string $id)
    {
        $jobApplication = JobApplication::withTrashed()->findOrFail($id);
        $jobApplication->restore();
        return redirect()->route('job-applications.index', ['archived' => 'true'])->with('success', 'Job application restored successfully!');
    }
}
