<?php

namespace App\Http\Controllers;

use App\Models\DataPertandingan;
use App\Models\DataVideo;
use App\Models\Player;
use App\Models\Starting11;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPertandinganController extends Controller
{
    // 📌 Display all match data
    public function index(Request $request)
    {
        $query = DataPertandingan::query();
    
        // ✅ Apply filters
        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('home_team')) {
            $query->where('home_team', 'LIKE', "%{$request->home_team}%");
        }
    
        // ✅ Sorting logic
        $sortableColumns = ['home_team', 'location']; // Allowed sorting columns
        $sortBy = $request->query('sortBy', 'tanggal'); // Default sort by date
        $sortOrder = $request->query('sortOrder', 'desc'); // Default order: desc
    
        if (in_array($sortBy, $sortableColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('tanggal', 'desc'); // Default sorting by date
        }
    
        // ✅ Paginate with filters and sorting applied
        $datapertandingan = $query->paginate(10)->appends($request->query());
    
        // ✅ Fetch distinct home teams for filter dropdown
        $teams = DataPertandingan::select('home_team')->distinct()->get();
    
        return view('admin.datapertandingan.index', compact('datapertandingan', 'teams', 'sortBy', 'sortOrder'));
    }
    

    // 📌 Show the form to create a new match
    public function create()
    {
        $teams = Team::all();
        $players = Player::with('user')->get(); // Fetch players with user data
        return view('admin.datapertandingan.create', compact('players', 'teams'));
    }

    // 📌 Store match details and redirect to the starting 11 page
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'match_summary' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // ✅ Added match summary validation
            'goal_scorers' => 'nullable|array',
            'goal_scorers.*.player' => 'nullable|string|max:255',
            'goal_scorers.*.minute' => 'nullable|integer|min:1',
            'yellow_cards' => 'nullable|array',
            'yellow_cards.*.player' => 'nullable|string|max:255',
            'yellow_cards.*.minute' => 'nullable|integer|min:1',
            'red_cards' => 'nullable|array',
            'red_cards.*.player' => 'nullable|string|max:255',
            'red_cards.*.minute' => 'nullable|integer|min:1',
        ]);
    
        // Handle Match Summary File Upload ✅
        if ($request->hasFile('match_summary')) {
            $path = $request->file('match_summary')->store('match_summaries', 'public');
            $validatedData['match_summary'] = $path;
        }
    
        // Ensure null arrays don't cause errors
        $validatedData['goal_scorers'] = $validatedData['goal_scorers'] ?? [];
        $validatedData['yellow_cards'] = $validatedData['yellow_cards'] ?? [];
        $validatedData['red_cards'] = $validatedData['red_cards'] ?? [];
    
        DB::transaction(function () use ($validatedData, &$match) {
            // ✅ Create the match record
            $match = DataPertandingan::create([
                'tanggal' => $validatedData['tanggal'],
                'home_team' => $validatedData['home_team'],
                'away_team' => $validatedData['away_team'],
                'home_score' => $validatedData['home_score'],
                'away_score' => $validatedData['away_score'],
                'location' => $validatedData['location'],
                'notes' => $validatedData['notes'],
                'match_summary' => $validatedData['match_summary'] ?? null,
            ]);
    
            // ✅ Insert Goals
            if (!empty($validatedData['goal_scorers'])) {
                foreach ($validatedData['goal_scorers'] as $goal) {
                    if (!empty($goal['player']) && !empty($goal['minute'])) {
                        $match->goals()->create([
                            'player' => $goal['player'],
                            'minute' => $goal['minute']
                        ]);
                    }
                }
            }
    
            // ✅ Insert Yellow Cards
            if (!empty($validatedData['yellow_cards'])) {
                foreach ($validatedData['yellow_cards'] as $card) {
                    if (!empty($card['player']) && !empty($card['minute'])) {
                        $match->yellowCards()->create([
                            'player' => $card['player'],
                            'minute' => $card['minute']
                        ]);
                    }
                }
            }
    
            // ✅ Insert Red Cards
            if (!empty($validatedData['red_cards'])) {
                foreach ($validatedData['red_cards'] as $card) {
                    if (!empty($card['player']) && !empty($card['minute'])) {
                        $match->redCards()->create([
                            'player' => $card['player'],
                            'minute' => $card['minute']
                        ]);
                    }
                }
            }
        });
    
        // ✅ Redirect directly to updateStarting11 to handle Starting 11 selection
        return redirect()->route('datapertandingan.editStarting11', ['datapertandingan' => $match->id])
        ->with('success', 'Data pertandingan berhasil disimpan! Lanjutkan ke Starting 11.');
    }    


    //  // 📌 Show Starting 11 selection + Drag & Drop positioning in **one page**
    //  public function starting11Page($matchId)
    //  {
    //      $datapertandingan = DataPertandingan::findOrFail($matchId);
     
    //      // 🔥 Retrieve players only from the selected home team
    //      $players = Player::whereHas('teams', function ($query) use ($datapertandingan) {
    //          $query->where('name', $datapertandingan->home_team);
    //      })->with('user')->get();
     
    //      // Retrieve already selected Starting 11 for positioning
    //      $startingPlayers = $datapertandingan->starting11()->get();
     
    //      return view('admin.datapertandingan.starting11', compact('datapertandingan', 'players', 'startingPlayers'));
    //  }     
 
    //  // 📌 Store Starting 11 & refresh page to show draggable positions
    //  public function storeStarting11(Request $request, $matchId)
    //  {
    //      $validatedData = $request->validate([
    //          'players' => 'required|array|min:11|max:11',
    //          'players.*.id' => 'nullable|exists:starting_11,id', // Ensure valid existing record
    //          'players.*.player_name' => 'required|string|max:255',
    //          'players.*.position' => 'required|string|max:50',
    //      ]);
     
    //      DB::transaction(function () use ($validatedData, $matchId) {
    //          foreach ($validatedData['players'] as $playerData) {
    //              if (!empty($playerData['id'])) {
    //                  Starting11::where('id', $playerData['id'])->update([
    //                      'player_name' => $playerData['player_name'],
    //                      'position' => $playerData['position'],
    //                  ]);
    //              } else {
    //                  Starting11::create([
    //                      'match_id' => $matchId,
    //                      'player_name' => $playerData['player_name'],
    //                      'position' => $playerData['position'],
    //                  ]);
    //              }
    //          }
    //      });
     
    //      return redirect()->route('datapertandingan.editStarting11', $matchId)
    //          ->with('success', 'Starting 11 berhasil diperbarui!');
    //  }
     
 
    //  // 📌 Store Player Positions via AJAX
    //  public function storePositions(Request $request, $matchId)
    //  {
    //      $request->validate([
    //          'positions' => 'required|string', // JSON string
    //      ]);
 
    //      $positions = json_decode($request->positions, true);
 
    //      if (!is_array($positions) || count($positions) === 0) {
    //          return response()->json(['error' => 'Setidaknya satu pemain harus dipindahkan.'], 400);
    //      }
 
    //      DB::transaction(function () use ($positions) {
    //          foreach ($positions as $position) {
    //              if (isset($position['player_id'], $position['x'], $position['y'])) {
    //                  Starting11::where('id', $position['player_id'])->update([
    //                      'x' => $position['x'],
    //                      'y' => $position['y'],
    //                  ]);
    //              }
    //          }
    //      });

    //      return redirect()->route('datapertandingan.index')
    //      ->with('success', 'Posisi pemain berhasil disimpan!');

    //  }

    // 📌 Show match details
    public function show($id)
    {
        $datapertandingan = DataPertandingan::with(['goals', 'yellowCards', 'redCards', 'starting11'])
            ->findOrFail($id);
    
        // Fetch related videos
        $videos = DataVideo::where('match_id', $id)->get();
    
        return view('admin.datapertandingan.show', compact('datapertandingan', 'videos'));
    }
    

    public function edit($matchId)
    {
        $datapertandingan = DataPertandingan::with(['goals', 'yellowCards', 'redCards'])->findOrFail($matchId);
        $teams = Team::all();
        return view('admin.datapertandingan.edit', compact('datapertandingan', 'teams'));
    }
    
    public function update(Request $request, $matchId)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'match_summary' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'goal_scorers' => 'nullable|array',
            'goal_scorers.*.player' => 'nullable|string|max:255',
            'goal_scorers.*.minute' => 'nullable|integer|min:1',
            'yellow_cards' => 'nullable|array',
            'yellow_cards.*.player' => 'nullable|string|max:255',
            'yellow_cards.*.minute' => 'nullable|integer|min:1',
            'red_cards' => 'nullable|array',
            'red_cards.*.player' => 'nullable|string|max:255',
            'red_cards.*.minute' => 'nullable|integer|min:1',
        ]);
    
        DB::transaction(function () use ($validatedData, $matchId, $request) {
            $match = DataPertandingan::findOrFail($matchId);
    
            // ✅ Update Match Details
            $match->update([
                'tanggal' => $validatedData['tanggal'],
                'home_team' => $validatedData['home_team'],
                'away_team' => $validatedData['away_team'],
                'home_score' => $validatedData['home_score'],
                'away_score' => $validatedData['away_score'],
                'location' => $validatedData['location'],
                'notes' => $validatedData['notes'],
            ]);
    
            // ✅ Handle Match Summary Upload
            if ($request->hasFile('match_summary')) {
                $path = $request->file('match_summary')->store('match_summaries', 'public');
                $match->update(['match_summary' => $path]);
            }
    
            // ✅ Update Goals, Yellow Cards, Red Cards
            $match->goals()->delete();
            foreach ($validatedData['goal_scorers'] ?? [] as $goal) {
                if (!empty($goal['player']) && !empty($goal['minute'])) {
                    $match->goals()->create($goal);
                }
            }
    
            $match->yellowCards()->delete();
            foreach ($validatedData['yellow_cards'] ?? [] as $card) {
                if (!empty($card['player']) && !empty($card['minute'])) {
                    $match->yellowCards()->create($card);
                }
            }
    
            $match->redCards()->delete();
            foreach ($validatedData['red_cards'] ?? [] as $card) {
                if (!empty($card['player']) && !empty($card['minute'])) {
                    $match->redCards()->create($card);
                }
            }
        });
    
        return redirect()->route('datapertandingan.editStarting11', $matchId)
            ->with('success', 'Data pertandingan berhasil diperbarui! Lanjutkan ke Starting 11.');
    }
    public function editStarting11($matchId)
{
    $datapertandingan = DataPertandingan::with('starting11')->findOrFail($matchId);

    // Fetch players only from the home team
    $players = Player::whereHas('teams', function ($query) use ($datapertandingan) {
        $query->where('name', $datapertandingan->home_team);
    })->with('user')->get();

    // Retrieve players from starting_11 table
    $startingPlayers = $datapertandingan->starting11->pluck('player_name')->toArray();

    return view('admin.datapertandingan.editStarting11', compact('datapertandingan', 'players', 'startingPlayers'));
}
  
public function updateStarting11(Request $request, $matchId)
{
    $validatedData = $request->validate([
        'players' => 'required|array|min:11|max:11',
        'players.*.id' => 'nullable|exists:starting_11,id', // Allow nullable IDs for new entries
        'players.*.player_name' => 'required|string|max:255',
        'players.*.position' => 'required|string|max:50',
    ]);

    DB::transaction(function () use ($validatedData, $matchId) {
        foreach ($validatedData['players'] as $playerData) {
            if (!empty($playerData['id'])) {
                // ✅ Update existing record
                Starting11::where('id', $playerData['id'])->update([
                    'player_name' => $playerData['player_name'], 
                    'position' => $playerData['position'],
                ]);
            } else {
                // ✅ Create new record for the match if not already added
                Starting11::create([
                    'match_id' => $matchId,
                    'player_name' => $playerData['player_name'],
                    'position' => $playerData['position'],
                ]);
            }
        }
    });

    return redirect()->route('datapertandingan.editStarting11', $matchId)
        ->with('success', 'Starting 11 berhasil diperbarui!');
}


    
    public function updatePositions(Request $request, $matchId)
    {
        $request->validate([
            'positions' => 'required|string',
        ]);
    
        $positions = json_decode($request->positions, true);
    
        DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                Starting11::where('id', $position['player_id'])->update([
                    'x' => $position['x'],
                    'y' => $position['y'],
                ]);
            }
        });
    
        return redirect()->route('datapertandingan.index')
            ->with('success', 'Posisi pemain berhasil diperbarui!');
    }
    

    // 📌 Delete match
    public function destroy($id)
    {
        $datapertandingan = DataPertandingan::findOrFail($id);
        $datapertandingan->delete();

        return redirect()->route('datapertandingan.index')->with('success', 'Data pertandingan berhasil dihapus!');
    }
}
