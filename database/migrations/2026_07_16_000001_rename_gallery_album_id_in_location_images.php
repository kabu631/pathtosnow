<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Rename location_images.gallery_album_id → location_id
 *
 * The previous migration attempted this rename but failed silently
 * because the foreign key constraint could not be dropped.
 * This migration completes the rename properly using raw SQL.
 *
 * After this runs:
 *  - Revert Location::images()    to: hasMany(LocationImage::class)
 *  - Revert LocationImage fillable to: ['location_id', ...]
 *  - Revert LocationImage::location() to: belongsTo(Location::class)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only run if the column hasn't been renamed yet
        if (!Schema::hasColumn('location_images', 'gallery_album_id')) {
            return; // Already renamed, nothing to do
        }

        // Drop foreign key using raw SQL (most reliable across MySQL versions)
        try {
            DB::statement('ALTER TABLE `location_images` DROP FOREIGN KEY `location_images_gallery_album_id_foreign`');
        } catch (\Exception $e) {
            // Try alternate constraint name patterns
            try {
                DB::statement('ALTER TABLE `location_images` DROP FOREIGN KEY `gallery_images_gallery_album_id_foreign`');
            } catch (\Exception $e2) {
                // Ignore — constraint may not exist by name
            }
        }

        // Rename the column
        DB::statement('ALTER TABLE `location_images` CHANGE `gallery_album_id` `location_id` BIGINT UNSIGNED NOT NULL');

        // Re-add the foreign key constraint
        try {
            DB::statement('ALTER TABLE `location_images` ADD CONSTRAINT `location_images_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Ignore if constraint already exists
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('location_images', 'location_id')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `location_images` DROP FOREIGN KEY `location_images_location_id_foreign`');
        } catch (\Exception $e) {}

        DB::statement('ALTER TABLE `location_images` CHANGE `location_id` `gallery_album_id` BIGINT UNSIGNED NOT NULL');
    }
};
