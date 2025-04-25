<?php

namespace App\Http\Controllers;

use App\Models\DataTes;
use App\Models\EvaluasiMandiri;
use App\Models\Player;
use App\Models\RaporPerkembangan;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaporPerkembanganController extends Controller
{
    public function index(Request $request)
    {
        $players = Player::with(['user', 'teams'])
            ->when($request->name, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', '%' . $request->name . '%')))
            ->when($request->bod, fn ($q) =>
                $q->whereYear('bod', $request->bod))
            ->when($request->position, fn ($q) =>
                $q->where('position', $request->position))
            ->when($request->team, fn ($q) =>
                $q->whereHas('teams', fn ($t) =>
                    $t->where('team_id', $request->team)))
            ->orderBy('bod', 'desc')
            ->get();
    
        $teams = Team::all();
    
        return view('admin.rapor_perkembangan.index', compact('players', 'teams'));
    }

    public function show($id)
{
    $player = Player::with([
        'user',
        'teams',
  // latest antropometri or test data
    ])->findOrFail($id);

    $rapor = RaporPerkembangan::with('positions')->where('player_id', $player->id)->first();

    $evaluasi = EvaluasiMandiri::where('user_id', $player->user_id)
        ->orderBy('tanggal', 'desc')
        ->first();
    $datates = DataTes::where('player_id', $player->id)->latest('tanggal')->first();


    return view('admin.rapor_perkembangan.show', compact('player', 'rapor', 'evaluasi', 'datates'));
}

public function updatePhoto(Request $request, $id)
{
    $request->validate([
        'photo_profile' => 'required|image|max:2048',
    ]);

    $player = Player::findOrFail($id);

    // Delete old photo if exists
    if ($player->photo_profile) {
        Storage::delete($player->photo_profile);
    }

    // Store new photo
    $path = $request->file('photo_profile')->store('player_photos', 'public');
    $player->photo_profile = $path;
    $player->save();

    return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
}

public function update(Request $request, $playerId)
{
    $request->validate([
        'deskripsi_umum' => 'required|string',
        'positions' => 'array|required',
    ]);

    $player = Player::findOrFail($playerId);

    $rapor = RaporPerkembangan::updateOrCreate(
        ['player_id' => $player->id],
        ['deskripsi_umum' => $request->deskripsi_umum]
    );

    // Sync positions
    $rapor->positions()->delete();
    foreach ($request->positions as $position) {
        $rapor->positions()->create(['position_code' => $position]);
    }

    return redirect()->back()->with('success', 'Data rapor berhasil diperbarui.');
}


}
