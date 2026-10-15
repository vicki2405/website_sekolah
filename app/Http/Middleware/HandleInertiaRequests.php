<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Cache;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();
        $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');

        $shared = [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'roles' => $user?->getRoleNames(),
                'isAdmin' => $isAdmin,
            ],
        ];

        $isAdminRoute = $request->is('admin/*') || $request->is('dashboard');

        if (!$isAdminRoute) {
            $shared['keahlian_menu'] = $this->getKeahlianMenu();
            $shared['waka_menu'] = $this->getWakaMenu();
            $shared['tefa_menu'] = $this->getTefaMenu();
            $shared['system_info_menu'] = $this->getSystemInformationMenu();
        }

        return $shared;
    }

    private function getWakaMenu()
    {
        try {
            return Cache::remember('public_waka_menu', now()->addHours(6), function () {
                return \App\Models\Waka::where('is_active', true)
                    ->orderBy('order')
                    ->get(['title', 'slug'])
                    ->toArray();
            });
        } catch (\Exception) {
            return [];
        }
    }

    private function getKeahlianMenu()
    {
        try {
            return Cache::remember('keahlian_menu_public', now()->addHours(6), function () {
                return \App\Models\Program::where('is_active', '=', true, 'and')
                    ->orderBy('order')
                    ->get(['name', 'slug'])
                    ->toArray();
            });
        } catch (\Exception) {
            return [];
        }
    }

    private function getSystemInformationMenu()
    {
        try {
            return Cache::remember('system_information_menu_public', now()->addHours(6), function () {
                return \App\Models\SystemInformationMenu::query()
                    ->orderBy('id')
                    ->get(['id', 'name', 'url'])
                    ->toArray();
            });
        } catch (\Exception) {
            return [];
        }
    }

    private function getTefaMenu()
    {
        try {
            return Cache::remember('public_tefa_menu', now()->addHours(6), function () {
                return \App\Models\Tefa::query()
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get(['title', 'slug'])
                    ->toArray();
            });
        } catch (\Exception) {
            return [];
        }
    }
}
