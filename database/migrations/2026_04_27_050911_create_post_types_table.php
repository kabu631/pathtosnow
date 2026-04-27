<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // "Food & Drink"
            $table->string('slug')->unique();          // "food-drink" — used in URL
            $table->string('type_key')->unique();      // "food" — stored in posts.post_type
            $table->string('icon_emoji')->default('📝');
            $table->string('color')->default('blue'); // Tailwind color name for badge
            $table->unsignedTinyInteger('sort_order')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_types');
    }
};
