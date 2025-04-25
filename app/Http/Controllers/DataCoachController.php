<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataCoachController extends Controller
{
    /**
     * Display the list of coaches.
     */
    public function index()
    {
        $coaches = User::where('role', 'Coach')->orderBy('name', 'asc')->paginate(10);
        return view('admin.datacoach.index', compact('coaches'));
    }

    /**
     * Show the form for creating a new coach.
     */
    public function create()
    {
        return view('admin.datacoach.create');
    }

    /**
     * Store a newly created coach in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // Create User as Coach
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Coach',
        ]);

        return redirect()->route('datacoach.index')->with('success', 'Data coach berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified coach.
     */
    public function edit($id)
    {
        $coach = User::where('role', 'Coach')->findOrFail($id);
        return view('admin.datacoach.edit', compact('coach'));
    }

    /**
     * Update the specified coach in the database.
     */
    public function update(Request $request, $id)
    {
        $coach = User::where('role', 'Coach')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $coach->id,
            'password' => 'nullable|min:8',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $coach->update($data);

        return redirect()->route('datacoach.index')->with('success', 'Data coach berhasil diperbarui!');
    }

    /**
     * Delete the specified coach.
     */
    public function destroy($id)
    {
        $coach = User::where('role', 'Coach')->findOrFail($id);
        $coach->delete();

        return redirect()->route('datacoach.index')->with('success', 'Data coach berhasil dihapus!');
    }
}
