<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiMandiri;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class EvaluasiMandiriController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with(['user.evaluasiMandiri', 'teams']);
    
        if ($request->team) {
            $query->whereHas('teams', function ($q) use ($request) {
                $q->where('teams.id', $request->team); // <<<<< FIX disini
            });
        }
    
        if ($request->evaluasi == 'ada') {
            $query->whereHas('user.evaluasiMandiri');
        } elseif ($request->evaluasi == 'tidak') {
            $query->whereDoesntHave('user.evaluasiMandiri');
        }
    
        $players = $query->paginate(10);
        $teams = Team::all();
    
        return view('admin.evaluasi_mandiri.index', compact('players', 'teams'));
    }
    
    

public function show($id)
{
    $player = Player::with(['user.evaluasiMandiri', 'teams'])->findOrFail($id);
    $evaluasiList = $player->user->evaluasiMandiri;

    return view('admin.evaluasi_mandiri.show', compact('player', 'evaluasiList'));
}



    
}
