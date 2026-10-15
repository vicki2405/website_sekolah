<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageCompression
{
    public function compressAndStore(
        UploadedFile $file,
        string $directory,
        int $quality = 60,
        ?int $maxWidth = 1200,
        ?int $targetMaxBytes = null,
        int $minQuality = 45,
        int $minWidth = 600
    ): string
    {
        $filename = Str::random(40) . '.webp';
        $tempPath = tempnam(sys_get_temp_dir(), 'img');
        
        $realPath = $file->getRealPath();
        $imageType = @exif_imagetype($realPath);

        if ($imageType === false) {
            $ext = strtolower($file->getClientOriginalExtension());
            $map = [
                'jpeg' => IMAGETYPE_JPEG,
                'jpg'  => IMAGETYPE_JPEG,
                'png'  => IMAGETYPE_PNG,
                'webp' => IMAGETYPE_WEBP,
                'gif'  => IMAGETYPE_GIF,
            ];
            $imageType = $map[$ext] ?? false;
        }

        if ($imageType === false) {
            return $file->storePublicly($directory, 'public');
        }

        $image = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($realPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($realPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($realPath),
            default        => false,
        };

        if ($image && $imageType === IMAGETYPE_PNG) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        if (!$image) {
            return $file->storePublicly($directory, 'public');
        }

        if (!function_exists('imagewebp')) {
            imagedestroy($image);
            return $file->storePublicly($directory, 'public');
        }

        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);

        $attemptWidth = $sourceWidth;
        if ($maxWidth && $sourceWidth > $maxWidth) {
            $attemptWidth = $maxWidth;
        }

        $attemptQuality = $quality;
        $attempts = 0;
        $maxAttempts = $targetMaxBytes ? 5 : 1;

        while ($attempts < $maxAttempts) {
            $attempts++;

            $renderWidth = $attemptWidth > 0 && $attemptWidth < $sourceWidth
                ? $attemptWidth
                : $sourceWidth;
            $renderHeight = (int) floor($sourceHeight * ($renderWidth / $sourceWidth));

            if ($renderWidth < $sourceWidth) {
                $render = imagecreatetruecolor($renderWidth, $renderHeight);
                imagealphablending($render, false);
                imagesavealpha($render, true);
                imagecopyresampled($render, $image, 0, 0, 0, 0, $renderWidth, $renderHeight, $sourceWidth, $sourceHeight);
            } else {
                $render = $image;
            }

            $saved = imagewebp($render, $tempPath, $attemptQuality);

            if ($render !== $image) {
                imagedestroy($render);
            }

            if (!$saved) {
                imagedestroy($image);
                if (is_string($tempPath) && file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                return $file->storePublicly($directory, 'public');
            }

            $size = @filesize($tempPath);
            if (!$targetMaxBytes || ($size !== false && $size <= $targetMaxBytes)) {
                break;
            }

            if ($attemptQuality > $minQuality) {
                $attemptQuality = max($minQuality, (int) round(($attemptQuality + $minQuality) / 2));
                continue;
            }

            if ($attemptWidth > $minWidth) {
                $sourceWidth = $renderWidth;
                $sourceHeight = $renderHeight;
                $attemptWidth = max($minWidth, (int) floor($attemptWidth * 0.85));
                $attemptQuality = $quality;
                continue;
            }

            break;
        }

        imagedestroy($image);

        $finalPath = rtrim($directory, '/') . '/' . $filename;
        Storage::disk('public')->put($finalPath, fopen($tempPath, 'r+'));
        
        unlink($tempPath);

        return $finalPath;
    }
}
