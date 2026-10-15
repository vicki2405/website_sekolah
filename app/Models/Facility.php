<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'gallery',
        'icon',
        'category',
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

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}

