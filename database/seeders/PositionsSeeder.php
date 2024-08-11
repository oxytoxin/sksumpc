<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    public function run(): void
    {
        Position::create(['name' => 'BOD Chairperson']);
        Position::create(['name' => 'BOD Vice-Chairperson']);
        Position::create(['name' => 'BOD Member']);
        Position::create(['name' => 'Election Committee']);
        Position::create(['name' => 'Audit Committee']);
        Position::create(['name' => 'Ethics Committee']);
        Position::create(['name' => 'Mediation Committee']);
        Position::create(['name' => 'Credit Committee']);
        Position::create(['name' => 'Education and Training Committee']);
        Position::create(['name' => 'Bids and Awards Committee']);
        Position::create(['name' => 'Gender and Development Committee']);
        Position::create(['name' => 'Treasurer']);
        Position::create(['name' => 'Secretary']);
        Position::create(['name' => 'Manager']);
        Position::create(['name' => 'Bookkeeper']);
        Position::create(['name' => 'Loan Officer']);
        Position::create(['name' => 'Teller']);
        Position::create(['name' => 'Clerk']);
        Position::create(['name' => 'Liaison Officer']);
        Position::create(['name' => 'Utility']);
    }
}
