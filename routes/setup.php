<?php
/**
 * SETUP & DIAGNOSTIC ROUTES
 * =========================
 * These routes help debug and fix issues with images and other settings.
 * All routes are protected by admin middleware.
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

// ONLY load these diagnostic routes in local/development — never on production
if (app()->environment(['local', 'development'])) {
    Route::middleware(['auth', 'admin'])->prefix('setup')->group(function () {

    // 1. Inspect DB paths
    Route::get('/inspect-db', function () {
        return response()->json([
            'products' => Product::get(['id', 'name', 'images']),
            'locations' => \App\Models\Location::get(['id', 'name', 'cover_image']),
        ]);
    });

    // 2. Fix images (migration & symlink replication)
    Route::get('/fix-images', function () {
        $storageBase     = storage_path('app/public');
        $publicStorage   = public_path('storage');      // keep old /storage/... URLs working
        $publicUploads   = public_path('images/uploads'); // new convention

        $copied  = 0;
        $skipped = 0;
        $errors  = [];

        // Helper for recursive copy
        $copyDir = function (string $src, string $dst) use (&$copyDir, &$copied, &$skipped, &$errors) {
            if (!is_dir($src)) return;
            if (!is_dir($dst)) mkdir($dst, 0755, true);

            foreach (scandir($src) as $item) {
                if ($item === '.' || $item === '..') continue;
                $srcPath = $src . DIRECTORY_SEPARATOR . $item;
                $dstPath = $dst . DIRECTORY_SEPARATOR . $item;

                if (is_dir($srcPath)) {
                    $copyDir($srcPath, $dstPath);
                } else {
                    if (!file_exists($dstPath)) {
                        if (copy($srcPath, $dstPath)) {
                            $copied++;
                        } else {
                            $errors[] = "Failed: {$srcPath}";
                        }
                    } else {
                        $skipped++;
                    }
                }
            }
        };

        // 1. Copy to public/storage/ (preserves old /storage/... URLs)
        $copyDir($storageBase, $publicStorage);

        // 2. Copy to public/images/uploads/ (new convention)
        $copyDir($storageBase, $publicUploads);

        // 3. Update DB: /storage/... → /images/uploads/...
        $dbUpdates = 0;
        $tableCols = [
            'locations'      => ['cover_image'],
            'location_images'=> ['image_path'],
            'gallery_albums' => ['cover_image'],
            'gallery_images' => ['image_path'],
            'slides'         => ['image'],
            'testimonials'   => ['avatar'],
            'posts'          => ['cover_image'],
        ];

        foreach ($tableCols as $table => $cols) {
            if (!Schema::hasTable($table)) continue;
            foreach ($cols as $col) {
                if (!Schema::hasColumn($table, $col)) continue;
                $n = DB::table($table)
                    ->where($col, 'like', '/storage/%')
                    ->update([$col => DB::raw("REPLACE(`{$col}`, '/storage/', '/images/uploads/')")]);
                $dbUpdates += $n;
            }
        }

        // Products.images and packages.gallery are JSON — update row by row
        foreach (['products' => 'images', 'packages' => 'gallery'] as $table => $col) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $col)) continue;
            foreach (DB::table($table)->whereNotNull($col)->get(['id', $col]) as $row) {
                $arr = json_decode($row->$col, true);
                if (!is_array($arr)) continue;
                $updated = array_map(fn($v) => is_string($v)
                    ? str_replace('/storage/', '/images/uploads/', $v)
                    : $v, $arr);
                if ($updated !== $arr) {
                    DB::table($table)->where('id', $row->id)->update([$col => json_encode($updated)]);
                    $dbUpdates++;
                }
            }
        }

        return response()->json([
            'status'     => empty($errors) ? '✅ Success' : '⚠️ Partial',
            'message'    => 'Images are now visible. Refresh any page to see them.',
            'files_copied'  => $copied,
            'files_skipped' => $skipped,
            'db_records_updated' => $dbUpdates,
            'errors'     => $errors,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    });

}); // end Route::middleware group
} // end app()->environment check
