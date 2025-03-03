<?php

use App\Enums\StatusType;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('label');
        });

        DB::table('statuses')->insert([
            [
                'name' => StatusType::INACTIVE->value,
                'label' => StatusType::INACTIVE->label(),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                'name' => StatusType::PENDING->value,
                'label' => StatusType::PENDING->label(),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                'name' => StatusType::ACTIVE->value,
                'label' => StatusType::ACTIVE->label(),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
