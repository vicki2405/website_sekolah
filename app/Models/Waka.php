<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waka extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'description',
        'image',
        'photo_name',
        'gallery',
        'order',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    public static function normalizeGallery(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        if (!is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $item) {
            $url = '';
            $caption = '';

            if (is_string($item)) {
                $url = trim($item);
            } elseif (is_array($item)) {
                $url = trim((string) ($item['url'] ?? ''));
                $caption = trim(strip_tags((string) ($item['caption'] ?? '')));
            } elseif (is_object($item)) {
                $url = trim((string) ($item->url ?? ''));
                $caption = trim(strip_tags((string) ($item->caption ?? '')));
            }

            if ($url === '') {
                continue;
            }

            $normalized[] = [
                'url' => $url,
                'caption' => $caption,
            ];
        }

        return $normalized;
    }

    public function getGalleryAttribute(mixed $value): array
    {
        return self::normalizeGallery($value);
    }
}
