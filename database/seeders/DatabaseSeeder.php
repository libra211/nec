<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            GeographicSeeder::class,
            UserSeeder::class,
            ContentSeeder::class,
            VoterDemoSeeder::class,
            CandidateSeeder::class,
            ElectionEventSeeder::class,
            ObserverSeeder::class,
            ContactSeeder::class,
            PermissionSeeder::class,
            AgentSeeder::class,
            CommissionerSeeder::class,
            PopulateAllSeeder::class,
        ]);

        $this->command->info('All seeders completed successfully!');
    }
}
