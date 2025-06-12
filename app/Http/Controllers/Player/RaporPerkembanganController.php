<?php

namespace App\Http\Controllers\Player;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataTes;
use App\Models\RaporPerkembangan;
use Illuminate\Support\Facades\Auth; // <-- Fix this

class RaporPerkembanganController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user has a related player profile
        if (!$user || !$user->player) {
            return redirect()->back()->with('error', 'Player profile not found.');
        }

        $playerId = $user->player->id;

        // Fetch all rapor perkembangan for this player
        $raporList = RaporPerkembangan::with(['player'])
                        ->where('player_id', $playerId)
                        ->latest()
                        ->get();

        return view('player.raporperkembangan.index', compact('raporList'));
    }

    public function show($raporId)
{
    $user = Auth::user();

    if (!$user || !$user->player) {
        return redirect()->back()->with('error', 'Profil pemain tidak ditemukan.');
    }

    $rapor = RaporPerkembangan::with(['positions', 'evaluasi', 'targets'])
        ->findOrFail($raporId);

    // Cegah akses jika rapor bukan milik pemain tersebut
    if ($rapor->player_id !== $user->player->id) {
        abort(403);
    }

    $player = $user->player;

    $datates = DataTes::where('player_id', $player->id)->latest('tanggal')->first();

    return view('player.raporperkembangan.show', compact('player', 'rapor', 'datates'));
}


}
