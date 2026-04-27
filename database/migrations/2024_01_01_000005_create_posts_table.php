<?php
// database/migrations/2024_01_01_000005_create_posts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_image')->nullable();
            // post_type: blog|food|culture|festival|city_tour|travel_guide
            $table->string('post_type')->default('blog');
            // category within type (e.g. "Dashain" for festival, "Pokhara" for city_tour)
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedSmallInteger('read_time')->default(5);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            // Related package (optional — link a post to a package)
            $table->foreignId('related_package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->timestamps();

            $table->index(['published', 'post_type']);
            $table->index('post_type');
        });
    }

    public function down(): void { Schema::dropIfExists('posts'); }
};
