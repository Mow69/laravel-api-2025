<?php

namespace Database\Seeders;

use App\Enums\StatusType;
use App\Models\Profil;
use App\Models\Status;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profil::factory(4)->create([
            'status_id' => Status::where('name', StatusType::ACTIVE->value)->first()->id
        ]);

        Profil::factory(2)->create([
            'status_id' => Status::where('name', StatusType::PENDING->value)->first()->id
        ]);

        Profil::factory(2)->create([
            'status_id' => Status::where('name', StatusType::INACTIVE->value)->first()->id
        ]);
    }
}
