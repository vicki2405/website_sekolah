<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon',
        'image', 'activity_images', 'industry_class_content',
        'competencies', 'careers',
        'duration', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activity_images' => 'array',
        'competencies' => 'array',
        'careers' => 'array',
    ];

    public static function normalizeActivityImages(mixed $value): array
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

    public function getActivityImagesAttribute(mixed $value): array
    {
        return self::normalizeActivityImages($value);
    }

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
