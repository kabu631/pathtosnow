<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Locations
        if (!Schema::hasTable('locations')) {
            if (Schema::hasTable('gallery_albums')) {
                Schema::rename('gallery_albums', 'locations');
                Schema::table('locations', function (Blueprint $table) {
                    $table->renameColumn('title', 'name');
                });
            } else {
                Schema::create('locations', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->text('description')->nullable();
                    $table->string('cover_image')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }
        }

        // 2. Location Images
        if (!Schema::hasTable('location_images')) {
            if (Schema::hasTable('gallery_images')) {
                Schema::rename('gallery_images', 'location_images');
                Schema::table('location_images', function (Blueprint $table) {
                    try {
                        $table->dropForeign(['gallery_album_id']);
                    } catch (\Exception $e) {}
                    $table->renameColumn('gallery_album_id', 'location_id');
                });
                Schema::table('location_images', function (Blueprint $table) {
                    $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete();
                });
            } else {
                Schema::create('location_images', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
                    $table->string('image_path');
                    $table->string('caption')->nullable();
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }
        }

        // 3. Testimonials
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->text('quote');
                $table->string('author');
                $table->string('location')->nullable();
                $table->string('avatar')->nullable();
                $table->integer('rating')->default(5);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed initial testimonials
            DB::table('testimonials')->insert([
                [
                    'quote' => "The trek to Annapurna Base Camp with PathToSnow was the adventure of a lifetime. The guide was incredibly professional, took perfect care of our group, and booking directly with them saved us over $300.",
                    'author' => "Brittany Clark",
                    'location' => "Sydney, Australia",
                    'avatar' => "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&h=120&fit=crop&q=80",
                    'rating' => 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'quote' => "Exceptional service! We customized a 3-day private safari in Chitwan and it was perfect. The resort, the river canoe ride, and seeing wild rhinos up close was mind-blowing.",
                    'author' => "Frances Hill",
                    'location' => "San Francisco, USA",
                    'avatar' => "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=120&h=120&fit=crop&q=80",
                    'rating' => 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'quote' => "Kathmandu Valley Tour was outstanding. Our local expert guide knew every secret corner, the history, and the best places to try traditional Newari cuisine. Strongly recommended!",
                    'author' => "Marc Dubois",
                    'location' => "Paris, France",
                    'avatar' => "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&h=120&fit=crop&q=80",
                    'rating' => 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 4. Update Packages Table
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'location_id')) {
                $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            }
            if (!Schema::hasColumn('packages', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('packages', 'original_price_nrs')) {
                $table->decimal('original_price_nrs', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('packages', 'discount_label')) {
                $table->string('discount_label')->nullable();
            }
            if (!Schema::hasColumn('packages', 'is_special_offer')) {
                $table->boolean('is_special_offer')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            try {
                $table->dropForeign(['location_id']);
            } catch (\Exception $e) {}
            $table->dropColumn(['location_id', 'original_price', 'original_price_nrs', 'discount_label', 'is_special_offer']);
        });

        Schema::dropIfExists('testimonials');
    }
};
