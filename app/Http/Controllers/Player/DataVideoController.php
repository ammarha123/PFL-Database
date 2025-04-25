<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataVideo;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataVideoController extends Controller
{public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses video.');
        }
    
        $user = Auth::user();
        $player = Player::where('user_id', $user->id)->first();
    
        if (!$player) {
            return redirect()->route('player.home')->with('error', 'Data pemain tidak ditemukan.');
        }
    
        // Get player_id
        $playerId = $player->id;
    
        // **Step 1: Get Match Videos**
        // - Find all match_id where the player is registered in player_team
        $matchIds = DB::table('player_team')
            ->where('player_id', $playerId)
            ->pluck('team_id');
    
        // - Fetch videos related to these match_ids
        $matchVideos = DataVideo::whereIn('match_id', $matchIds)->get();
    
        // **Step 2: Get Training Videos**
        // - Find all latihan_id where the player is registered in datalatihan_team
        $trainingIds = DB::table('datalatihan_player')
            ->where('player_id', $playerId)
            ->pluck('datalatihan_id');
    
        // - Fetch videos related to these latihan_ids
        $trainingVideos = DataVideo::whereIn('latihan_id', $trainingIds)->get();
    
        // **Step 3: Other Videos (if needed)**
        $otherVideos = DataVideo::whereNotIn('id', $matchVideos->pluck('id')->merge($trainingVideos->pluck('id')))->get();
    
        return view('player.datavideo.index', compact(
            'matchVideos',
            'trainingVideos',
            'otherVideos'
        ));
    }    
}
