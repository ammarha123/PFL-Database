<?php

namespace App\Http\Controllers;

use App\Models\DataTes;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataTesController extends Controller
{
    public function index(Request $request)
    {
        $query = DataTes::with('player'); // ✅ Load player and user relationship
    
        // 🔍 Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
    
        // 🔍 Filter by Player Name
        if ($request->filled('player_id')) {
            $query->where('player_id', $request->player_id);
        }
    
        // 🔍 Filter by Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
    
        // 🔄 Sorting Logic
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortBy, $sortOrder);
        }
    
        // 🔹 Paginate Results
        $datates = $query->paginate(10);
    
        // Fetch all players for filtering
        $players = Player::with('user')->get();
    
        return view('admin.datates.index', compact('datates', 'players'));
    }
    


    public function create()
    {
        $players = Player::with('user')->get(); // Fetch players with their user data
        $teams = Team::all();
        return view('admin.datates.create', compact('players', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'test_type' => 'required|string', // Individual or Team
            'category' => 'required|string',
            'tanggal' => 'required|date',
    
            // Individual Test
            'player_id' => 'nullable|exists:players,id',
    
            // Team Test
            'team_id' => 'nullable|exists:teams,id',
            'players' => 'nullable|array',
            'players.*.id' => 'nullable|exists:players,id',
    
            // Anthropometry
            'players.*.weight' => 'nullable|numeric',
            'players.*.height' => 'nullable|numeric',
            'players.*.bmi' => 'nullable|numeric',
            'players.*.fat_chest' => 'nullable|numeric',
            'players.*.fat_thighs' => 'nullable|numeric',
            'players.*.fat_abs' => 'nullable|numeric',
            'players.*.fat_percentage' => 'nullable|numeric',
    
            // FMS
            'players.*.fms_squat' => 'nullable|integer|min:0|max:3',
            'players.*.fms_hurdle' => 'nullable|integer|min:0|max:3',
            'players.*.fms_lunge' => 'nullable|integer|min:0|max:3',
            'players.*.fms_shoulder' => 'nullable|integer|min:0|max:3',
            'players.*.fms_leg_raise' => 'nullable|integer|min:0|max:3',
            'players.*.fms_push_up' => 'nullable|integer|min:0|max:3',
            'players.*.fms_rotary' => 'nullable|integer|min:0|max:3',
            'players.*.fms_total' => 'nullable|integer|min:0|max:21',
    
            // VO2Max & MAS
            'players.*.vo2max_type' => 'nullable|numeric',
            'players.*.vo2max_duration' => 'nullable|numeric',
            'players.*.speed' => 'nullable|numeric',
            'players.*.oxygen' => 'nullable|numeric',
            'players.*.vo2max_score' => 'nullable|numeric',
    
            'players.*.mas_type' => 'nullable|numeric',
            'players.*.mas_speed' => 'nullable|numeric',
            'players.*.mas_duration' => 'nullable|numeric',
            'players.*.mas_distance' => 'nullable|numeric',
        ]);
    
        // ✅ Store Individual Test
        if ($request->test_type === 'individual') {
            DataTes::create([
                'player_id' => $validated['player_id'],
                'category' => $validated['category'],
                'tanggal' => $validated['tanggal'],
                'weight' => $request->input('weight') ?? null,
                'height' => $request->input('height') ?? null,
                'bmi' => $request->input('bmi') ?? null,
                'fat_chest' => $request->input('fat_chest') ?? null,
                'fat_thighs' => $request->input('fat_thighs') ?? null,
                'fat_abs' => $request->input('fat_abs') ?? null,
                'fat_percentage' => $request->input('fat_percentage') ?? null,
                'fms_squat' => $request->input('fms_squat') ?? null,
                'fms_hurdle' => $request->input('fms_hurdle') ?? null,
                'fms_lunge' => $request->input('fms_lunge') ?? null,
                'fms_shoulder' => $request->input('fms_shoulder') ?? null,
                'fms_leg_raise' => $request->input('fms_leg_raise') ?? null,
                'fms_push_up' => $request->input('fms_push_up') ?? null,
                'fms_rotary' => $request->input('fms_rotary') ?? null,
                'fms_total' => $request->input('fms_total') ?? null,
                'vo2max_type' => $request->input('vo2max_type') ?? null,
                'vo2max_duration' => $request->input('vo2max_duration') ?? null,
                'speed' => $request->input('speed') ?? null,
                'oxygen' => $request->input('oxygen') ?? null,
                'vo2max_score' => $request->input('vo2max_score') ?? null,
                'mas_type' => $request->input('mas_type') ?? null,
                'mas_speed' => $request->input('mas_speed') ?? null,
                'mas_duration' => $request->input('mas_duration') ?? null,
                'mas_distance' => $request->input('mas_distance') ?? null,
            ]);
        } 
        // ✅ Store Team Test (Multiple Players)
        else {
            $teamPlayers = Player::whereHas('teams', function ($query) use ($validated) {
                $query->where('teams.id', $validated['team_id']);
            })->get();
            
            foreach ($teamPlayers as $player) {
                $playerData = $validated['players'][$player->id] ?? [];
            
                // Check if any relevant field is filled (non-null & not empty)
                $hasData = collect($playerData)->filter(function ($value) {
                    return $value !== null && $value !== '';
                })->isNotEmpty();
            
                if (!$hasData) {
                    continue; // Skip saving this player
                }
            
                // Create only if there's valid data
                DataTes::create([
                    'player_id' => $player->id,
                    'category' => $validated['category'],
                    'tanggal' => $validated['tanggal'],
                    'weight' => $playerData['weight'] ?? null,
                    'height' => $playerData['height'] ?? null,
                    'bmi' => $playerData['bmi'] ?? null,
                    'fat_chest' => $playerData['fat_chest'] ?? null,
                    'fat_thighs' => $playerData['fat_thighs'] ?? null,
                    'fat_abs' => $playerData['fat_abs'] ?? null,
                    'fat_percentage' => $playerData['fat_percentage'] ?? null,
                    'fms_squat' => $playerData['fms_squat'] ?? null,
                    'fms_hurdle' => $playerData['fms_hurdle'] ?? null,
                    'fms_lunge' => $playerData['fms_lunge'] ?? null,
                    'fms_shoulder' => $playerData['fms_shoulder'] ?? null,
                    'fms_leg_raise' => $playerData['fms_leg_raise'] ?? null,
                    'fms_push_up' => $playerData['fms_push_up'] ?? null,
                    'fms_rotary' => $playerData['fms_rotary'] ?? null,
                    'fms_total' => $playerData['fms_total'] ?? null,
                    'vo2max_type' => $playerData['vo2max_type'] ?? null,
                    'vo2max_duration' => $playerData['vo2max_duration'] ?? null,
                    'speed' => $playerData['speed'] ?? null,
                    'oxygen' => $playerData['oxygen'] ?? null,
                    'vo2max_score' => $playerData['vo2max_score'] ?? null,
                    'mas_type' => $playerData['mas_type'] ?? null,
                    'mas_speed' => $playerData['mas_speed'] ?? null,
                    'mas_duration' => $playerData['mas_duration'] ?? null,
                    'mas_distance' => $playerData['mas_distance'] ?? null,
                ]);
            }            
        }
    
        return redirect()->route('datates.index')->with('success', 'Data tes berhasil disimpan!');
    }
    
    
    

public function getTeamPlayers($teamId)
{
    $players = Player::whereHas('teams', function ($query) use ($teamId) {
        $query->where('team_id', $teamId);
    })->with('user')->get(['id', 'user_id', 'bod', 'position']);

    return response()->json($players->map(function ($player) {
        return [
            'id' => $player->id,
            'name' => $player->user->name,
            'bod' => $player->bod,
            'position' => $player->position
        ];
    }));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $datates = DataTes::findOrFail($id);
        $players = Player::with('user')->get();
    
        return view('admin.datates.edit', compact('datates', 'players'));
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $datates = DataTes::findOrFail($id);
    
        $validated = $request->validate([
            'tanggal' => 'required|date',
    
            // Anthropometry
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'fat_chest' => 'nullable|numeric',
            'fat_thighs' => 'nullable|numeric',
            'fat_abs' => 'nullable|numeric',
            'fat_percentage' => 'nullable|numeric',
    
            // FMS
            'fms_squat' => 'nullable|integer|min:0|max:3',
            'fms_hurdle' => 'nullable|integer|min:0|max:3',
            'fms_lunge' => 'nullable|integer|min:0|max:3',
            'fms_shoulder' => 'nullable|integer|min:0|max:3',
            'fms_leg_raise' => 'nullable|integer|min:0|max:3',
            'fms_push_up' => 'nullable|integer|min:0|max:3',
            'fms_rotary' => 'nullable|integer|min:0|max:3',
            'fms_total' => 'nullable|integer|min:0|max:21',
    
            // VO2Max & MAS
            'vo2max_type' => 'nullable|numeric',
            'vo2max_duration' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'oxygen' => 'nullable|numeric',
            'vo2max_score' => 'nullable|numeric',
    
            'mas_type' => 'nullable|numeric',
            'mas_speed' => 'nullable|numeric',
            'mas_duration' => 'nullable|numeric',
            'mas_distance' => 'nullable|numeric',
        ]);
    
        $datates->update($validated);
    
        return redirect()->route('datates.index')->with('success', 'Data tes berhasil diperbarui!');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $datates = DataTes::findOrFail($id); // Fetch the specific Data Tes record

        // Delete the associated file
        Storage::disk('public')->delete($datates->data_tes_path);

        // Delete the record from the database
        $datates->delete();

        return redirect()->route('datates.index')->with('success', 'Data tes berhasil dihapus!');
    }

    public function show($id)
    {
        $datates = DataTes::with('player.user')->findOrFail($id);
        return view('admin.datates.show', compact('datates'));
    }    
}
