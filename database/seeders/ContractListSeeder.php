<?php

namespace Database\Seeders;

use App\Models\ContractList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Throwable
     */
    public function run(): void
    {
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            for ($i = 0; $i < 20; $i++) {
                retry(5, function() use ($user) {
                    return ContractList::factory()->create([
                        'user_id' => $user->id,
                    ]);
                });
            }
        }
    }
}
