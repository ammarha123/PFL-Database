<?php
namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\DataTes;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataTesController extends Controller
{
    /**
     * Display categorized test data for the logged-in player.
     */
    public function index()
    {
        $user = Auth::user();

        // Get player ID based on logged-in user
        $player = Player::where('user_id', $user->id)->first();

        // Check if player data exists
        if (!$player) {
            return redirect()->route('player.home')->with('error', 'Data pemain tidak ditemukan.');
        }

        // Define test categories
        $categories = ['Antropometri', 'FMS', 'VO2Max', 'MAS'];

        // Retrieve test data for the player sorted by date
        $dataTes = DataTes::where('player_id', $player->id)->orderBy('tanggal', 'desc')->get();

        // Structure the data for each category
        $categorizedTests = [];
        foreach ($categories as $category) {
            $categorizedTests[$category] = $dataTes->where('category', $category);
        }

        return view('player.datates.index', compact('categorizedTests', 'categories'));
    }

    /**
     * Show test details.
     */
    public function show($id)
    {
        $test = DataTes::findOrFail($id);

        return view('player.datates.show', compact('test'));
    }
}
