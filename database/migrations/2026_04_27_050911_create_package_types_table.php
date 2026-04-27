<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // "Adventure"
            $table->string('slug')->unique();                // "adventure" — used in URL
            $table->string('type_key')->unique();            // "adventure" — stored in packages.type
            $table->string('description')->nullable();
            $table->string('icon_emoji')->default('📦');
            $table->string('hero_image_url')->nullable();    // Unsplash / uploaded URL
            $table->string('gradient')->default('from-slate-700 to-slate-800'); // Tailwind classes
            $table->string('badge_class')->default('bg-slate-100 text-slate-700');
            $table->unsignedTinyInteger('sort_order')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_types');
    }
};
