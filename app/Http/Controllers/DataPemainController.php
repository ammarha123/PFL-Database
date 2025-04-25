<?php

namespace App\Http\Controllers;

use App\Imports\PlayerImport;
use App\Models\User;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DataPemainController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'Player')->with('player');
    
        // Search by Name
        if (!empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
    
        // Filter by Position
        if (!empty($request->position)) {
            $query->whereHas('player', function ($q) use ($request) {
                $q->where('position', $request->position);
            });
        }
    
        // Filter by Category
        if (!empty($request->category)) {
            $query->whereHas('player', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }
    
        // Sorting (Check if column exists in `players` table)
        $validSortColumns = ['name', 'bod', 'category', 'position'];
        $sortBy = in_array($request->sortBy, $validSortColumns) ? $request->sortBy : 'id';
        $sortDirection = $request->sortDirection === 'desc' ? 'desc' : 'asc';
    
        // Sorting on related table
        if (in_array($sortBy, ['bod', 'category', 'position'])) {
            $query->join('players', 'users.id', '=', 'players.user_id')
                ->orderBy('players.' . $sortBy, $sortDirection)
                ->select('users.*');
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }
    
        // Pagination with items per page
        $perPage = $request->perPage ?? 10;
        $datapemain = $query->paginate($perPage);
    
        return view('admin.datapemain.index', compact('datapemain'));
    }
    
    public function create()
    {
        $teams = Team::all(); // Fetch all teams
        return view('admin.datapemain.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bod' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'position' => 'required|string',
            'photo_profile' => 'nullable|image|max:2048', // Allow profile photo
            'teams' => 'required|array',  // Ensure at least one team is selected
        ]);

        // Handle Profile Picture Upload
        if ($request->hasFile('photo_profile')) {
            $path = $request->file('photo_profile')->store('profiles', 'public');
            $validated['photo_profile'] = $path;
        }

        // Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Player',
        ]);

        // Create Player and link to User
        $player = Player::create([
            'user_id' => $user->id,
            'bod' => $validated['bod'],
            'position' => $validated['position'],
            'photo_profile' => $validated['photo_profile'] ?? null,
        ]);

        // Attach Player to Teams
        $player->teams()->attach($request->teams);

        return redirect()->route('datapemain.index')->with('success', 'Data pemain berhasil ditambahkan!');
    }

    public function show($id)
    {
        $datapemain = User::where('id', $id)
            ->where('role', 'Player')
            ->with('player.teams')
            ->firstOrFail();
        return view('admin.datapemain.show', compact('datapemain'));
    }

    public function edit($id)
    {
        $datapemain = User::where('id', $id)
            ->where('role', 'Player')
            ->with('player.teams')
            ->firstOrFail();
        $teams = Team::all(); // Fetch all teams
        return view('admin.datapemain.edit', compact('datapemain', 'teams'));
    }

    public function update(Request $request, $id)
{
    $datapemain = User::findOrFail($id);
    $player = $datapemain->player; // Get related player

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bod' => 'required|date',
        'email' => 'required|email|unique:users,email,' . $id,
        'position' => 'required|string',
        'teams' => 'required|array',  // Ensure at least one team is selected
    ]);

    // Update User details
    $datapemain->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
    ]);

    // Update Player details
    if ($player) {
        $player->update([
            'bod' => $validated['bod'],
            'position' => $validated['position'],
        ]);

        // ✅ **Sync Player with Selected Teams**
        $player->teams()->sync($request->teams);
    }

    return redirect()->route('datapemain.index')->with('success', 'Data pemain berhasil diperbarui!');
}


    public function destroy($id)
    {
        $datapemain = User::findOrFail($id);

        // Delete related player first
        if ($datapemain->player) {
            // Delete profile picture if exists
            if ($datapemain->player->photo_profile) {
                Storage::disk('public')->delete($datapemain->player->photo_profile);
            }

            // Detach from teams before deleting
            $datapemain->player->teams()->detach();
            $datapemain->player->delete();
        }

        // Delete User
        $datapemain->delete();

        return redirect()->route('datapemain.index')->with('success', 'Data pemain berhasil dihapus!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new PlayerImport, $request->file('file'));

        return redirect()->route('datapemain.index')->with('success', 'Data pemain berhasil diimport!');
    }
}
