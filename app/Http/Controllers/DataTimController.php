<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class DataTimController extends Controller
{
    /**
     * Display the list of teams.
     */
    public function index()
    {
        $teams = Team::orderBy('name', 'asc')->paginate(10); // Pagination & Sorting
        return view('admin.datatim.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team.
     */
    public function create()
    {
        return view('admin.datatim.create');
    }

    /**
     * Store a newly created team in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
        ]);

        Team::create(['name' => $request->name]);

        return redirect()->route('datatim.index')->with('success', 'Tim berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.datatim.edit', compact('team'));
    }

    /**
     * Update the specified team in the database.
     */
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
        ]);

        $team->update(['name' => $request->name]);

        return redirect()->route('datatim.index')->with('success', 'Tim berhasil diperbarui!');
    }

    /**
     * Delete the specified team.
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('datatim.index')->with('success', 'Tim berhasil dihapus!');
    }
}
