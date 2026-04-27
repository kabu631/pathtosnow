<?php
// database/migrations/2024_01_01_000002_create_packages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // adventure|valley_visit|trekking|national_park|wildlife_reserve|lake
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location');           // Pokhara, Kathmandu Valley, Annapurna Region...
            $table->string('region')->nullable();  // Province or area
            $table->text('short_description');
            $table->longText('description');
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();   // array of image paths

            // Pricing & logistics
            $table->decimal('price_per_person', 10, 2);
            $table->decimal('price_group', 10, 2)->nullable(); // group price if different
            $table->integer('duration_days');
            $table->integer('duration_nights')->nullable();
            $table->integer('min_group_size')->default(1);
            $table->integer('max_group_size')->default(15);

            // Difficulty / specifics
            $table->enum('difficulty', ['easy','moderate','challenging','strenuous'])->nullable();
            $table->integer('max_altitude_m')->nullable();    // for treks/parks
            $table->string('best_season')->nullable();        // "Mar-May, Sep-Nov"
            $table->string('start_point')->nullable();
            $table->string('end_point')->nullable();

            // What's included
            $table->json('highlights')->nullable();           // array of highlight strings
            $table->json('included')->nullable();             // array: "Guide", "Permits"...
            $table->json('excluded')->nullable();             // array: "International flights"...
            $table->json('requirements')->nullable();         // fitness, gear list
            $table->json('faqs')->nullable();                 // [{q:..., a:...}]

            // Status
            $table->boolean('featured')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('views')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();

            $table->index('type');
            $table->index('featured');
            $table->index('active');
        });
    }

    public function down(): void { Schema::dropIfExists('packages'); }
};
