<?php

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class StatesCitiesSeeder extends Seeder {

    public function run() {
        
        $dir = 'database/sql/';
        
        DB::unprepared(file_get_contents($dir.'states.sql'));
        DB::unprepared(file_get_contents($dir.'cities.sql'));
        
    }
}

