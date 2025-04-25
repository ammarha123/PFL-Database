<?php

namespace App\Http\Controllers;

use App\Models\DataLatihan;
use App\Models\DataVideo;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataLatihanController extends Controller
{
    // Show all latihan data
    public function index(Request $request)
    {
        $query = DataLatihan::with('teams'); // Load related teams
    
        // 🔍 Apply Date Filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
    
        // 🔍 Filter by Team (Pivot Table)
        if ($request->filled('team_id')) {
            $query->whereHas('teams', function ($q) use ($request) {
                $q->where('teams.id', $request->team_id);
            });
        }
    
        $datalatihan = $query->paginate(10);
        $teams = Team::all(); // Fetch all teams for filtering
    
        return view('admin.datalatihan.index', compact('datalatihan', 'teams'));
    }
    

    // Show form to create new latihan data
    public function create()
    {
        $teams = Team::all();
        $players = Player::with('user')->get(); // Fetch players with their user data
        return view('admin.datalatihan.create', compact('players', 'teams'));
    }
    
    public function getPlayersByTeam($teamId)
{
    $players = Player::whereHas('teams', function ($query) use ($teamId) {
        $query->where('team_id', $teamId);
    })->with('user')->get(['id', 'user_id']);

    return response()->json($players->map(function ($player) {
        return [
            'id' => $player->id,
            'name' => $player->user->name
        ];
    }));
}

    // Store latihan data
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'tanggal' => 'required|date',
        'latihan_file' => 'required|file|mimes:pdf,doc,docx,xlsx,csv|max:2048',
        'team_id' => 'required|exists:teams,id', // Validate team selection
        'players' => 'required|array', // Ensure players are selected
        'players.*.id' => 'exists:players,id', // Validate each player exists
        'players.*.status' => 'required|in:Hadir,Tidak Hadir', // Validate attendance status
        'players.*.reason' => 'nullable|string|max:255', // Validate reason if "Tidak Hadir"
    ]);

    // Store the latihan file
    $filePath = $request->file('latihan_file')->store('latihan_files', 'public');

    // Create DataLatihan record
    $datalatihan = DataLatihan::create([
        'tanggal' => $validatedData['tanggal'],
        'latihan_file_path' => $filePath,
    ]);

    // ✅ Attach the team to the correct pivot table `datalatihan_team`
    $datalatihan->teams()->attach($validatedData['team_id']);

    // ✅ Attach players with their attendance records to `datalatihan_player`
    foreach ($validatedData['players'] as $player) {
        $datalatihan->players()->attach($player['id'], [
            'status' => $player['status'],
            'reason' => $player['status'] === 'Tidak Hadir' ? $player['reason'] : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('datalatihan.index')->with('success', 'Data latihan berhasil disimpan!');
}

    // Show specific latihan data
    public function show(DataLatihan $datalatihan)
    {
        $datalatihan->load('players'); // Load associated players
        $videos = DataVideo::where('latihan_id', $datalatihan->id)->get();
        return view('admin.datalatihan.show', compact('datalatihan','videos'));
    }

    // Edit latihan data
    public function edit($id)
    {
        $datalatihan = Datalatihan::with('teams', 'players')->findOrFail($id);
    $teams = Team::all();

    // Fetch players who belong to the selected teams and their attendance data
    $players = Player::whereHas('teams', function ($query) use ($datalatihan) {
        $query->whereIn('team_id', $datalatihan->teams->pluck('id'));
    })
    ->leftJoin('datalatihan_player', function ($join) use ($id) {
        $join->on('players.id', '=', 'datalatihan_player.player_id')
             ->where('datalatihan_player.datalatihan_id', $id);
    })
    ->select(
        'players.id',
        'players.user_id',
        'players.position',
        'datalatihan_player.status',
        'datalatihan_player.reason'
    )
    ->with('user:id,name')
    ->get()
    ->map(function ($player) {
        return [
            'id' => $player->id,
            'name' => $player->user->name,
            'status' => $player->status ?? 'Hadir', // Default to 'Hadir' if null
            'reason' => $player->reason ?? '',
        ];
    });

    return view('admin.datalatihan.edit', compact('datalatihan', 'teams', 'players'));
    }
    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'latihan_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,csv|max:2048',
            'team_id' => 'required|exists:teams,id', 
            'players' => 'nullable|array', 
            'players.*.id' => 'exists:players,id', 
            'players.*.status' => 'required|in:Hadir,Tidak Hadir', 
            'players.*.reason' => 'nullable|string|max:255', 
        ]);
    
        $datalatihan = DataLatihan::findOrFail($id);
    
        if ($request->hasFile('latihan_file')) {
            if ($datalatihan->latihan_file_path) {
                Storage::disk('public')->delete($datalatihan->latihan_file_path);
            }
            $filePath = $request->file('latihan_file')->store('latihan_files', 'public');
            $datalatihan->latihan_file_path = $filePath;
        }
    
        $datalatihan->update([
            'tanggal' => $validatedData['tanggal'],
        ]);
    
        $datalatihan->teams()->sync([$validatedData['team_id']]);
    
        // Update players and their attendance
        $datalatihan->players()->detach(); 
        foreach ($validatedData['players'] as $player) {
            $datalatihan->players()->attach($player['id'], [
                'status' => $player['status'],
                'reason' => $player['status'] === 'Tidak Hadir' ? $player['reason'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        return redirect()->route('datalatihan.index')->with('success', 'Data latihan berhasil diperbarui!');
    }
    

    // Delete latihan data
    public function destroy(DataLatihan $datalatihan)
    {
        // Delete file
        if ($datalatihan->latihan_file_path) {
            Storage::disk('public')->delete($datalatihan->latihan_file_path);
        }

        // Detach players from the pivot table
        $datalatihan->players()->detach();

        // Delete DataLatihan record
        $datalatihan->delete();

        return redirect()->route('datalatihan.index')->with('success', 'Data latihan berhasil dihapus!');
    }
}
