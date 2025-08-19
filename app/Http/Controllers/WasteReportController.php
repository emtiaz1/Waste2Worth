<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteReport;
use Illuminate\Support\Facades\DB;

class WasteReportController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'waste_type' => 'required|string',
            'amount' => 'required|numeric',
            'unit' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('waste_images', 'public');
        }

        $report = WasteReport::create([
            'waste_type' => $data['waste_type'],
            'amount' => $data['amount'],
            'unit' => $data['unit'],
            'location' => $data['location'],
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
        ]);

        return response()->json(['success' => true, 'report' => $report]);
    }

    public function recent()
    {
        $reports = WasteReport::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($reports);
    }

    public function stats()
    {
        $total = WasteReport::sum('amount');
        $mostType = WasteReport::select('waste_type', DB::raw('COUNT(*) as count'))
            ->groupBy('waste_type')
            ->orderByDesc('count')
            ->first();

        // Get waste breakdown by type for environmental impact calculations
        $wasteByType = WasteReport::select('waste_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('waste_type')
            ->pluck('total_amount', 'waste_type')
            ->toArray();

        // Community Activity Metrics - Database Driven
        
        // Today's reports count
        $todayReports = WasteReport::whereDate('created_at', today())->count();
        
        // Active contributors today (unique users who reported today)
        $activeContributors = WasteReport::whereDate('created_at', today())
            ->distinct('location') // Using location as user identifier for now
            ->count('location');
        
        // This week's reports for goal tracking
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyReports = WasteReport::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        
        // Weekly goal (can be made configurable)
        $weeklyGoal = 50;
        $weeklyProgress = min($weeklyReports, $weeklyGoal);
        
        // This month's total for monthly tracking
        $monthlyReports = WasteReport::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // Top waste type today
        $todayTopType = WasteReport::select('waste_type', DB::raw('COUNT(*) as count'))
            ->whereDate('created_at', today())
            ->groupBy('waste_type')
            ->orderByDesc('count')
            ->first();

        return response()->json([
            'total' => $total,
            'mostType' => $mostType ? $mostType->waste_type : 'None',
            'wasteByType' => $wasteByType,
            // Community Activity Data
            'communityActivity' => [
                'todayReports' => $todayReports,
                'activeContributors' => $activeContributors,
                'weeklyReports' => $weeklyReports,
                'weeklyGoal' => $weeklyGoal,
                'weeklyProgress' => $weeklyProgress,
                'monthlyReports' => $monthlyReports,
                'todayTopType' => $todayTopType ? $todayTopType->waste_type : 'None'
            ]
        ]);
    }

    public function communityActivity()
    {
        // Real-time community metrics from database
        
        // Today's statistics
        $today = today();
        $todayReports = WasteReport::whereDate('created_at', $today)->count();
        $todayWasteAmount = WasteReport::whereDate('created_at', $today)->sum('amount');
        
        // This week's statistics
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyReports = WasteReport::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        
        // Active contributors (unique locations today - in real app, would be user_id)
        $activeContributors = WasteReport::whereDate('created_at', $today)
            ->distinct('location')
            ->count('location');
        
        // Hourly activity today for activity tracking
        $hourlyActivity = WasteReport::whereDate('created_at', $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();
        
        // Recent top contributors by location
        $topContributors = WasteReport::select('location', DB::raw('COUNT(*) as report_count'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('location')
            ->orderByDesc('report_count')
            ->limit(5)
            ->get();
        
        // Daily trend (last 7 days)
        $dailyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = WasteReport::whereDate('created_at', $date)->count();
            $dailyTrend[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        return response()->json([
            'todayReports' => $todayReports,
            'todayWasteAmount' => round($todayWasteAmount, 1),
            'activeContributors' => $activeContributors,
            'weeklyReports' => $weeklyReports,
            'weeklyGoal' => 50, // Configurable goal
            'hourlyActivity' => $hourlyActivity,
            'topContributors' => $topContributors,
            'dailyTrend' => $dailyTrend,
            'lastUpdate' => now()->toISOString()
        ]);
    }
}
