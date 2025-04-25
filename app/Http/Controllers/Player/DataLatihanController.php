<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\DataLatihan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class DataLatihanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'Player') {
            abort(403, 'Unauthorized action.');
        }

        // Get player info
        $player = Player::where('user_id', $user->id)->first();
        if ($user->role !== 'Player') {
            abort(403, 'Unauthorized action.');
        }

        // Get player info
        $player = Player::where('user_id', $user->id)->first();
        if (!$player) {
            return redirect()->back()->with('error', 'Player data not found.');
        }

        // Get training session IDs where the player is listed in datalatihan_player
        $trainingIds = DB::table('datalatihan_player')
            ->where('player_id', $player->id)
            ->pluck('datalatihan_id');

        // Get only the training sessions that match these IDs
        $datalatihan = DataLatihan::whereIn('id', $trainingIds)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('player.datalatihan.index', compact('datalatihan'));
    }
}
