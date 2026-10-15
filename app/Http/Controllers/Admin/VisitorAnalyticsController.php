<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorAnalyticsController extends Controller
{
    /**
     * Display visitor analytics dashboard.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // 1. KPI Statistics
        $totalPageviews = VisitorLog::count();
        $totalUniqueVisitors = VisitorLog::distinct('ip_address')->count('ip_address');

        $todayPageviews = VisitorLog::whereDate('created_at', $today)->count();
        $todayUniqueVisitors = VisitorLog::whereDate('created_at', $today)->distinct('ip_address')->count('ip_address');

        $yesterdayUniqueVisitors = VisitorLog::whereDate('created_at', Carbon::yesterday())->distinct('ip_address')->count('ip_address');
        $thisMonthUniqueVisitors = VisitorLog::where('created_at', '>=', $thisMonth)->distinct('ip_address')->count('ip_address');

        // Growth rate compared to yesterday
        $growthRate = 0;
        if ($yesterdayUniqueVisitors > 0) {
            $growthRate = round((($todayUniqueVisitors - $yesterdayUniqueVisitors) / $yesterdayUniqueVisitors) * 100, 1);
        }

        // 2. Chart Trend Data (Last 14 Days)
        $chartDays = 14;
        $chartLabels = [];
        $chartVisitorsData = [];
        $chartPageviewsData = [];

        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $label = $date->translatedFormat('d M');

            $chartLabels[] = $label;

            $dayUnique = VisitorLog::whereDate('created_at', $dateString)->distinct('ip_address')->count('ip_address');
            $dayViews = VisitorLog::whereDate('created_at', $dateString)->count();

            $chartVisitorsData[] = $dayUnique;
            $chartPageviewsData[] = $dayViews;
        }

        // 3. Device Breakdown
        $devices = VisitorLog::select('device', DB::raw('count(*) as count'))
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();

        $totalDeviceCount = $devices->sum('count') ?: 1;
        $deviceBreakdown = $devices->map(function ($item) use ($totalDeviceCount) {
            $percentage = round(($item->count / $totalDeviceCount) * 100, 1);
            return [
                'device'     => $item->device,
                'count'      => $item->count,
                'percentage' => $percentage,
            ];
        });

        // 4. Browser Breakdown
        $browsers = VisitorLog::select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 5. Top Visited Pages
        $topPages = VisitorLog::select('url', 'page_title', DB::raw('count(*) as views'))
            ->groupBy('url', 'page_title')
            ->orderByDesc('views')
            ->limit(7)
            ->get();

        $maxPageView = $topPages->max('views') ?: 1;

        // 6. Recent Logs (Paginated)
        $recentLogs = VisitorLog::latest()
            ->paginate(15);

        return view('admin.visitors', [
            'totalPageviews'          => $totalPageviews,
            'totalUniqueVisitors'     => $totalUniqueVisitors,
            'todayPageviews'          => $todayPageviews,
            'todayUniqueVisitors'     => $todayUniqueVisitors,
            'yesterdayUniqueVisitors' => $yesterdayUniqueVisitors,
            'thisMonthUniqueVisitors' => $thisMonthUniqueVisitors,
            'growthRate'              => $growthRate,
            'chartLabels'             => $chartLabels,
            'chartVisitorsData'       => $chartVisitorsData,
            'chartPageviewsData'      => $chartPageviewsData,
            'deviceBreakdown'         => $deviceBreakdown,
            'browsers'                => $browsers,
            'topPages'                => $topPages,
            'maxPageView'             => $maxPageView,
            'recentLogs'              => $recentLogs,
        ]);
    }

    /**
     * Clear / reset visitor logs if needed.
     */
    public function reset(Request $request)
    {
        VisitorLog::truncate();

        return redirect()->route('admin.visitors.index')->with('success', 'Data log pengunjung berhasil dibersihkan.');
    }
}
