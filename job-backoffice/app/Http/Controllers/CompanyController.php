<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\getConnection;

class CompanyController extends Controller
{

    public $industries = ['Technology', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'Logistics', 'Engineering', 'Other'];

    public function index(Request $request)
    {
         $query = Company::query();

        // Add search functionality
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', '%' . addcslashes($searchTerm, '%_\\') . '%');
        }

        // Archived 
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed()->orderBy('deleted_at', 'desc');
        } 
        // Active 
        else {
            $query->latest(); 
        }
        $companies = $query->paginate(10)->onEachSide(1)->withQueryString();
        $archivedCount = $query->onlyTrashed()->count();
        return view('company.index', compact('companies', 'archivedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries = $this->industries;
        return view('company.create', compact('industries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyCreateRequest $request)
    {
        $validated = $request->validated();
        // Create Owner
        $owner = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => Hash::make($validated['owner_password']),
            'role' => 'company-owner',
        ]);

        // Return error if owner is not created
        if (!$owner) {
            return redirect()->route('companies.create')->with('error', 'Owner creation failed!');
        }

        // Create Company
        Company::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'ownerId' => $owner->id
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id = null)
    {
        $company = $this->getCompany($id);
       
        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id = null)
    {
        $company = $this->getCompany($id);
        $industries = $this->  industries;

        return view('company.edit', compact('company', 'industries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateeRequest $request, string $id = null)
    {
        $validated = $request->validated();
        $company = $this->getCompany($id);

        // Update Owner
        $ownerDate = [];
        $ownerDate['name'] = $validated['owner_name'];

        if($validated['owner_password']){
            $ownerDate['password'] = Hash::make($validated['owner_password']);
        }

        $company->owner->update($ownerDate);

        // Update Company
        $company->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
        ]);

        $company->update($validated);

        if((Auth::user()->role == 'company-owner')){
            return redirect()->route('my-company.show')->with('success', 'Company updated successfully!');
        }

        if($request->query('redirectToList') == 'false'){
            return redirect()->route('companies.show', $id)->with('success', 'Company updated successfully!');
        }
        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company archived successfully!');
    }

     public function restore(string $id)
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();
        return redirect()->route('companies.index', ['archived' => 'true'])->with('success', 'Company restored successfully!');
    }

    private function getCompany(string $id = null)
    {
        if($id){
            return Company::findOrFail($id);
        } 

         return Company::where('ownerId', Auth::user()->id)->first();
    }
}
