<?php
// database/migrations/2024_01_01_000003_create_itinerary_days_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('itinerary_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('day_number');
            $table->string('title');                      // "Day 1: Arrival in Pokhara"
            $table->text('description');                  // What happens this day
            $table->string('accommodation')->nullable();   // "Teahouse / Hotel"
            $table->json('meals')->nullable();             // ["Breakfast","Lunch","Dinner"]
            $table->unsignedInteger('distance_km')->nullable();
            $table->unsignedInteger('altitude_m')->nullable();      // camp altitude
            $table->unsignedInteger('elevation_gain_m')->nullable(); // ascent
            $table->unsignedInteger('elevation_loss_m')->nullable(); // descent
            $table->string('place_name')->nullable();      // specific location reached
            $table->text('notes')->nullable();             // tips for this day
            $table->timestamps();

            $table->unique(['package_id', 'day_number']);
            $table->index('package_id');
        });
    }

    public function down(): void { Schema::dropIfExists('itinerary_days'); }
};
