<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class PlayerImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row
        if ($row[0] === 'No' || empty($row[1])) {
            return null;
        }

        // Extract necessary data from the row
        $fullName = trim($row[1]); // Nama (Column B)
        $day = $row[2] ?? null; // Tanggal (Column C)
        $month = $row[3] ?? null; // Bulan (Column D)
        $year = $row[4] ?? null; // Tahun (Column E)
        $positionCode = strtoupper(trim($row[5] ?? '')); // Posisi (Column F)
        $category = $row[7] ?? 'Unknown'; // Category (Column H)

        // Convert Date of Birth (BOD) to proper format
        if (!empty($year) && !empty($month) && !empty($day)) {
            $bod = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
        } else {
            $bod = null; // Handle missing date values
        }

        // Extract second name or first if only one exists for the username
        $nameParts = explode(' ', $fullName);
        $username = strtolower($nameParts[1] ?? $nameParts[0]) . '@gmail.com';

        // Convert position code to full position name
        $positions = [
            'GK' => 'Goalkeeper',
            'FW' => 'Forward',
            'MF' => 'Midfielder',
            'DF' => 'Defender'
        ];
        $position = $positions[$positionCode] ?? 'Unknown';

        // Check if email already exists to avoid duplicates
        $existingUser = User::where('email', $username)->first();

        if (!$existingUser) {
            // Create User
            $user = User::create([
                'name' => $fullName,
                'email' => $username,
                'password' => Hash::make('password123'), // Default password
                'role' => 'Player',
            ]);

            // Create Player
            Player::create([
                'user_id' => $user->id,
                'bod' => $bod,
                'category' => $category,
                'position' => $position,
            ]);
        }

        return null;
    }
}
