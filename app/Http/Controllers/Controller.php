<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function forgetPublicCaches(array|string $areas, array $context = []): void
    {
        foreach ($this->publicCacheKeys($areas, $context) as $key) {
            Cache::forget($key);
        }
    }

    protected function publicCacheKeys(array|string $areas, array $context = []): array
    {
        $areas = is_array($areas) ? $areas : [$areas];
        $keys = [];

        foreach ($areas as $area) {
            switch ($area) {
                case 'agenda':
                    $keys[] = 'public_agendas_payload_v1';
                    $keys[] = 'public_agendas_payload_v2';
                    $keys[] = 'public_home_payload_v1';
                    break;

                case 'home':
                    $keys[] = 'public_home_payload_v1';
                    break;

                case 'about':
                    $keys[] = 'public_about_payload_v1';
                    break;

                case 'news':
                    $keys[] = 'public_home_payload_v1';
                    $keys[] = 'public_news_categories';
                    $keys = array_merge(
                        $keys,
                        $this->detailCacheKeys('public_news_show:', (array) ($context['slugs'] ?? [])),
                        $this->detailCacheKeys('public_news_show_v3:', (array) ($context['slugs'] ?? [])),
                        $this->detailCacheKeys('public_news_related:', (array) ($context['slugs'] ?? [])),
                        $this->detailCacheKeys('public_news_related_v3:', (array) ($context['slugs'] ?? []))
                    );
                    break;

                case 'programs':
                    $keys[] = 'public_programs_list_v1';
                    $keys[] = 'keahlian_menu_public';
                    $keys[] = 'public_home_payload_v1';
                    $keys = array_merge(
                        $keys,
                        $this->detailCacheKeys('public_program_by_slug_v1:', (array) ($context['slugs'] ?? []))
                    );
                    break;

                case 'prestasi':
                    $keys[] = 'prestasi_page_settings';
                    $keys[] = 'public_prestasi_achievements_v1';
                    $keys[] = 'public_home_payload_v1';
                    $keys[] = 'public_about_payload_v1';
                    break;

                case 'teachers':
                    $keys[] = 'public_teachers_payload_v2';
                    $keys[] = 'public_home_payload_v1';
                    $keys[] = 'public_about_payload_v1';
                    break;

                case 'facilities':
                    $keys[] = 'public_facilities_payload_v1';
                    break;

                case 'gallery':
                    $keys[] = 'public_gallery_payload_v1';
                    $keys[] = 'public_home_payload_v1';
                    break;

                case 'waka':
                    $keys[] = 'public_waka_menu';
                    $keys[] = 'public_all_waka_list_v1';
                    $keys = array_merge(
                        $keys,
                        $this->detailCacheKeys('public_waka_by_slug_v1:', (array) ($context['slugs'] ?? []))
                    );
                    break;

                case 'tefa':
                    $keys[] = 'public_tefa_menu';
                    $keys = array_merge(
                        $keys,
                        $this->detailCacheKeys('public_tefa_by_slug_v1:', (array) ($context['slugs'] ?? []))
                    );
                    break;

                case 'system_information':
                    $keys[] = 'system_information_menu_public';
                    break;

                case 'profile':
                    $keys[] = 'public_about_payload_v1';
                    break;
            }
        }

        return array_values(array_unique(array_filter($keys)));
    }

    protected function detailCacheKeys(string $prefix, array $slugs): array
    {
        $normalizedSlugs = array_values(array_unique(array_filter(array_map(
            fn ($slug) => trim((string) $slug),
            $slugs
        ))));

        return array_map(
            fn (string $slug) => $prefix . $slug,
            $normalizedSlugs
        );
    }
}
