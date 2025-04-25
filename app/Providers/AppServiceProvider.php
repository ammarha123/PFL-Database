<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\DataPertandingan;
use App\Models\Starting11;
use App\Models\DataLatihan;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $user = Auth::user();

            if ($user && $user->role === 'Player') {
                // Match Activeness Calculation
                $totalMatches = DataPertandingan::count();
                $matchesPlayed = Starting11::where('player_name', $user->name)->count();
                $matchActiveness = ($totalMatches > 0) ? ($matchesPlayed / $totalMatches) * 100 : 0;

                // Get player's ID
                $player = Player::where('user_id', $user->id)->first();

                 // Get team_id from player_team table
                $team = DB::table('player_team')
                ->where('player_id', $player->id)
                ->join('teams', 'player_team.team_id', '=', 'teams.id')
                ->select('teams.name as team_name')
                ->first();

                // Pass team name to all views
                $teamName = $team ? $team->team_name : null;

                if ($player) {
                    // Get relevant training session IDs where the player participated
                    $relevantTrainingSessions = DB::table('datalatihan_player')
                        ->where('player_id', $player->id)
                        ->pluck('datalatihan_id');

                    // Total relevant training sessions
                    $totalTrainingSessions = DB::table('datalatihan')
                        ->whereIn('id', $relevantTrainingSessions)
                        ->count();

                    // Count attended sessions (hadir = 1) from datalatihan_player
                    $totalAttendedTrainings = DB::table('datalatihan_player')
                        ->where('player_id', $player->id)
                        ->where('status', "Hadir")
                        ->count();
                } else {
                    $totalTrainingSessions = 0;
                    $totalAttendedTrainings = 0;
                }

                // Calculate training activeness
                $trainingActiveness = ($totalTrainingSessions > 0) ? ($totalAttendedTrainings / $totalTrainingSessions) * 100 : 0;

                $view->with(compact('trainingActiveness', 'matchActiveness', 'player', 'teamName'));
            }
        });
    }
}
