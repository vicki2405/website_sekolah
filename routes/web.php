<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [HomeController::class, 'about'])->name('about');

// Event Submenu
Route::prefix('event')->group(function () {
    Route::get('/agenda', [PublicController::class, 'agenda'])->name('event.agenda');
    Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
    Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
});

Route::get('/prestasi', [PublicController::class, 'prestasi'])->name('prestasi');

// Keahlian Submenu
Route::prefix('keahlian')->group(function () {
    Route::get('/', [PublicController::class, 'programs'])->name('keahlian.index');
    Route::get('/{slug}', [PublicController::class, 'keahlianShow'])->name('keahlian.show');
});

Route::get('/galeri', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/guru', [PublicController::class, 'teachers'])->name('teachers');

// WAKA Submenu
Route::prefix('waka')->group(function () {
    Route::get('/{slug}', [PublicController::class, 'wakaShow'])->name('waka.show');
});

// TEFA Submenu
Route::prefix('tefa')->group(function () {
    Route::get('/{slug}', [PublicController::class, 'tefaShow'])->name('tefa.show');
});

Route::get('/fasilitas', [PublicController::class, 'facilities'])->name('facilities');
Route::get('/fasilitas/{id}', [PublicController::class, 'facilityShow'])->name('facilities.show');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::post('/kontak', [PublicController::class, 'contactStore'])->middleware('throttle:contact')->name('contact.store');

// Redirects for old URLs to prevent 404
Route::get('/tentang', fn() => redirect()->route('about'));
Route::get('/program', fn() => redirect()->route('keahlian.index'));
Route::get('/berita', fn() => redirect()->route('news.index'));
Route::get('/berita/{slug}', fn($slug) => redirect()->route('news.show', $slug));

// Admin Routes
Route::get('/dashboard', function () {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    if ($user && method_exists($user, 'can') && $user->can('admin.access.view') && $user->can('admin.dashboard.view')) {
        if (request()->header('X-Inertia')) {
            return Inertia::location(route('admin.dashboard'));
        }

        return redirect()->route('admin.dashboard');
    }

    if (request()->header('X-Inertia')) {
        return Inertia::location(route('home'));
    }

    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'permission:admin.access.view'])
    ->group(function () {
        Route::get('/', function () {
            $user = Auth::user();
            if ($user && method_exists($user, 'can') && $user->can('admin.dashboard.view')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        });

        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'newsCount'            => \Illuminate\Support\Facades\DB::table('news')->count(),
                'programCount'         => \Illuminate\Support\Facades\DB::table('programs')->count(),
                'teacherCount'         => \Illuminate\Support\Facades\DB::table('teachers')->count(),
                'achievementCount'     => \Illuminate\Support\Facades\DB::table('achievements')->count(),
                'todayVisitorCount'    => \App\Models\VisitorLog::whereDate('created_at', \Carbon\Carbon::today())->distinct('ip_address')->count('ip_address'),
                'totalVisitorCount'    => \App\Models\VisitorLog::distinct('ip_address')->count('ip_address'),
                'recentNews'           => \App\Models\News::query()->latest()->take(5)->get(['id', 'title', 'is_published', 'created_at']),
            ]);
        })->name('dashboard')->middleware('permission:admin.dashboard.view');

        // Visitor Analytics Route
        Route::get('pengunjung', [\App\Http\Controllers\Admin\VisitorAnalyticsController::class, 'index'])->name('visitors.index');
        Route::post('pengunjung/reset', [\App\Http\Controllers\Admin\VisitorAnalyticsController::class, 'reset'])->name('visitors.reset');

        // Home Management (Aggregate)
        // Backward compatibility: old URL /admin/sliders now points to Home
        Route::get('sliders', fn () => redirect()->route('admin.home.index'))->name('sliders.redirect');
        Route::get('sliders/{any}', fn () => redirect()->route('admin.home.index'))
            ->where('any', '.*')
            ->name('sliders.redirect.any');

        Route::get('home', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home.index');
        Route::get('home/sliders/create', [\App\Http\Controllers\Admin\HomeController::class, 'sliderCreate'])->name('home.sliders.create');
        Route::get('home/sliders/{slider}/edit', [\App\Http\Controllers\Admin\HomeController::class, 'sliderEdit'])->name('home.sliders.edit');
        Route::post('home/sliders', [\App\Http\Controllers\Admin\HomeController::class, 'sliderStore'])->name('home.sliders.store');
        Route::put('home/sliders/{slider}', [\App\Http\Controllers\Admin\HomeController::class, 'sliderUpdate'])->name('home.sliders.update');
        Route::delete('home/sliders/{slider}', [\App\Http\Controllers\Admin\HomeController::class, 'sliderDestroy'])->name('home.sliders.destroy');

        Route::get('home/partners/create', [\App\Http\Controllers\Admin\HomeController::class, 'partnerCreate'])->name('home.partners.create');
        Route::get('home/partners/{partner}/edit', [\App\Http\Controllers\Admin\HomeController::class, 'partnerEdit'])->name('home.partners.edit');
        Route::post('home/partners', [\App\Http\Controllers\Admin\HomeController::class, 'partnerStore'])->name('home.partners.store');
        Route::put('home/partners/{partner}', [\App\Http\Controllers\Admin\HomeController::class, 'partnerUpdate'])->name('home.partners.update');
        Route::delete('home/partners/{partner}', [\App\Http\Controllers\Admin\HomeController::class, 'partnerDestroy'])->name('home.partners.destroy');

        Route::get('home/testimonials/create', [\App\Http\Controllers\Admin\HomeController::class, 'testimonialCreate'])->name('home.testimonials.create');
        Route::get('home/testimonials/{testimonial}/edit', [\App\Http\Controllers\Admin\HomeController::class, 'testimonialEdit'])->name('home.testimonials.edit');
        Route::post('home/testimonials', [\App\Http\Controllers\Admin\HomeController::class, 'testimonialStore'])->name('home.testimonials.store');
        Route::put('home/testimonials/{testimonial}', [\App\Http\Controllers\Admin\HomeController::class, 'testimonialUpdate'])->name('home.testimonials.update');
        Route::delete('home/testimonials/{testimonial}', [\App\Http\Controllers\Admin\HomeController::class, 'testimonialDestroy'])->name('home.testimonials.destroy');

        Route::post('home/sections', [\App\Http\Controllers\Admin\HomeController::class, 'sectionUpdate'])->name('home.sections.update');

        Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        Route::get('prestasi', [AdminAchievementController::class, 'index'])->name('prestasi.index');
        Route::get('prestasi/create', [AdminAchievementController::class, 'create'])->name('prestasi.create');
        Route::get('prestasi/{achievement}/edit', [AdminAchievementController::class, 'edit'])->name('prestasi.edit');
        Route::post('prestasi', [AdminAchievementController::class, 'store'])->name('prestasi.store');
        Route::put('prestasi/{achievement}', [AdminAchievementController::class, 'update'])->name('prestasi.update');
        Route::delete('prestasi/{achievement}', [AdminAchievementController::class, 'destroy'])->name('prestasi.destroy');
        Route::post('prestasi/settings', [AdminAchievementController::class, 'updatePageSettings'])->name('prestasi.settings.update');

        Route::resource('waka', \App\Http\Controllers\Admin\WakaController::class)->except(['show']);
        Route::resource('tefa', \App\Http\Controllers\Admin\TefaController::class)->except(['show']);
        Route::post('tefa/editor-images', [\App\Http\Controllers\Admin\TefaController::class, 'editorImageStore'])->name('tefa.editor-images.store');

        Route::resource('achievements', AdminAchievementController::class)->except(['show', 'create', 'edit']);
        Route::resource('programs', AdminProgramController::class)->except(['show']);
        Route::post('programs/editor-images', [AdminProgramController::class, 'editorImageStore'])->name('programs.editor-images.store');
        Route::get('news/create', [AdminNewsController::class, 'create'])->name('news.create');
        Route::get('news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
        Route::post('news/editor-images', [AdminNewsController::class, 'editorImageStore'])->name('news.editor-images.store');
        Route::resource('news', AdminNewsController::class)->except(['show', 'create', 'edit']);
        Route::resource('agendas', AdminAgendaController::class)->except(['show', 'create', 'edit']);
        Route::get('teachers/create', [AdminTeacherController::class, 'create'])->name('teachers.create');
        Route::get('teachers/{teacher}/edit', [AdminTeacherController::class, 'edit'])->name('teachers.edit');
        Route::resource('teachers', AdminTeacherController::class)->except(['show', 'create', 'edit']);
        Route::resource('teacher-categories', \App\Http\Controllers\Admin\TeacherCategoryController::class)->except(['show']);
        Route::get('system-information', [\App\Http\Controllers\Admin\SystemInformationMenuController::class, 'index'])->name('system-information.index');
        Route::post('system-information', [\App\Http\Controllers\Admin\SystemInformationMenuController::class, 'store'])->name('system-information.store');
        Route::delete('system-information/{menu}', [\App\Http\Controllers\Admin\SystemInformationMenuController::class, 'destroy'])->name('system-information.destroy');
        Route::get('facilities/categories', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'index'])->name('facilities.categories.index');
        Route::get('facilities/categories/create', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'create'])->name('facilities.categories.create');
        Route::post('facilities/categories', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'store'])->name('facilities.categories.store');
        Route::get('facilities/categories/{category}/edit', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'edit'])->name('facilities.categories.edit');
        Route::put('facilities/categories/{category}', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'update'])->name('facilities.categories.update');
        Route::delete('facilities/categories/{category}', [\App\Http\Controllers\Admin\FacilityCategoryController::class, 'destroy'])->name('facilities.categories.destroy');
        Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class)->except(['show']);
        Route::get('gallery/categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'index'])->name('gallery.categories.index');
        Route::get('gallery/categories/create', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'create'])->name('gallery.categories.create');
        Route::post('gallery/categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'store'])->name('gallery.categories.store');
        Route::get('gallery/categories/{category}/edit', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'edit'])->name('gallery.categories.edit');
        Route::put('gallery/categories/{category}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'update'])->name('gallery.categories.update');
        Route::delete('gallery/categories/{category}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'destroy'])->name('gallery.categories.destroy');
        Route::resource('gallery', AdminGalleryController::class)->except(['show', 'create', 'edit']);
        Route::post('contacts/settings', [AdminContactController::class, 'updateSettings'])->name('contacts.settings.update');
        Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);

        Route::prefix('access')->name('access.')->group(function () {
            Route::resource('users', AdminUserController::class)->except(['show']);
            Route::resource('roles', AdminRoleController::class)->except(['show']);
        });

        Route::get('backup', [AdminBackupController::class, 'index'])->name('backup.index');
        Route::get('backup/export', [AdminBackupController::class, 'export'])->name('backup.export')->middleware('throttle:backup');
        Route::post('backup/import', [AdminBackupController::class, 'import'])->name('backup.import')->middleware(['throttle:backup']);

        Route::post('teachers/org/nodes', [AdminTeacherController::class, 'orgNodeStore'])->name('teachers.org.nodes.store');
        Route::put('teachers/org/nodes/{node}', [AdminTeacherController::class, 'orgNodeUpdate'])->name('teachers.org.nodes.update');
        Route::delete('teachers/org/nodes/{node}', [AdminTeacherController::class, 'orgNodeDestroy'])->name('teachers.org.nodes.destroy');
        Route::post('teachers/org/assign', [AdminTeacherController::class, 'orgAssign'])->name('teachers.org.assign');
        Route::delete('teachers/org/nodes/{node}/teachers/{teacher}', [AdminTeacherController::class, 'orgUnassign'])->name('teachers.org.unassign');
        Route::post('teachers/org/bootstrap', [AdminTeacherController::class, 'orgBootstrap'])->name('teachers.org.bootstrap');
        Route::post('teachers/org-image/upload', [AdminTeacherController::class, 'orgImageUpload'])->name('teachers.org-image.upload');
        Route::delete('teachers/org-image/delete', [AdminTeacherController::class, 'orgImageDelete'])->name('teachers.org-image.delete');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Universal Storage Image Fallback (Securely delivers public assets if symlink is missing on shared hosting)
Route::get('/storage/{path}', function (string $path) {
    // 1. Prevent directory traversal attacks
    if (str_contains($path, '..') || str_contains($path, "\0")) {
        abort(404);
    }

    // 2. Only allow safe public media extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'pdf', 'mp4', 'webm', 'mp3', 'woff', 'woff2', 'ttf', 'eot'];
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        abort(404);
    }

    $searchBases = array_filter([
        realpath(public_path('storage')),
        realpath(storage_path('app/public')),
    ]);

    foreach ($searchBases as $base) {
        $candidate = realpath($base . '/' . $path);
        if ($candidate && is_file($candidate) && str_starts_with($candidate, $base)) {
            $mime = @mime_content_type($candidate) ?: 'application/octet-stream';
            return response()->file($candidate, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }
    }

    abort(404);
})->where('path', '.*');

require __DIR__.'/auth.php';
