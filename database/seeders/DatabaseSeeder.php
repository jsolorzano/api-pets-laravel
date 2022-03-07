<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        
        // Create families with default values
        $families = ['Canino', 'Felino', 'Equino', 'Roedor', 'Porcino'];
        
        foreach($families as $f){
			Family::create([
				'name' => $f
			]);
		}
    }
}
