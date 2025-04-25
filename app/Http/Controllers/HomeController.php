<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DataPertandingan;
use App\Models\Starting11;
use App\Models\DataLatihan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get logged-in user

        if ($user->role === 'Admin') {
            return view('admin.index');
        } elseif ($user->role === 'Coach') {
            return view('coach.index');
        } elseif ($user->role === 'Player') {
            return view('player.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
