<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateeRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

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
        $users = $query->paginate(10)->onEachSide(1)->withQueryString();
        $archivedCount = $query->onlyTrashed()->count();
        return view('user.index', compact('users', 'archivedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateeRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
           'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('users.index')->with('success', 'Password updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobApplication = User::findOrFail($id);
        $jobApplication->delete();
        return redirect()->route('users.index')->with('success', 'User archived successfully!');
    }

     public function restore(string $id)
    {
        $jobApplication = User::withTrashed()->findOrFail($id);
        $jobApplication->restore();
        return redirect()->route('users.index', ['archived' => 'true'])->with('success', 'user restored successfully!');
    }
}
