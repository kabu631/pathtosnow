<?php
/**
 * ImageService — stores uploaded images directly into public/images/uploads/
 * so they are instantly web-accessible at /images/uploads/...
 * No storage:link symlink required.
 */

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store an uploaded file into public/images/uploads/{folder}/
     * and return the web-accessible URL path (e.g. /images/uploads/slides/abc.jpg)
     *
     * @param UploadedFile $file
     * @param string       $folder   Sub-folder inside public/images/uploads/
     * @return string  Web path starting with /images/uploads/...
     */
    public static function store(UploadedFile $file, string $folder = 'general'): string
    {
        $folder   = trim($folder, '/');
        $dir      = public_path("images/uploads/{$folder}");

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext      = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $filename = Str::uuid() . '.' . $ext;

        $file->move($dir, $filename);

        return "/images/uploads/{$folder}/{$filename}";
    }

    /**
     * Delete a file that was stored via ImageService.
     * Pass the web path (e.g. /images/uploads/slides/abc.jpg)
     */
    public static function delete(?string $webPath): void
    {
        if (!$webPath) return;

        // Support both old /storage/... paths and new /images/uploads/... paths
        if (str_starts_with($webPath, '/images/uploads/')) {
            $fullPath = public_path(ltrim($webPath, '/'));
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    /**
     * Translate an old /storage/... path to its new /images/uploads/... equivalent.
     * Used only during the one-time migration.
     */
    public static function migratedPath(string $oldPath): string
    {
        // /storage/slides/abc.jpg  →  /images/uploads/slides/abc.jpg
        return str_replace('/storage/', '/images/uploads/', $oldPath);
    }
}
