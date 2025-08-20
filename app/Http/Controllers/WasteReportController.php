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
            'user_email' => auth()->user()->email, // Fix: Add current user's email
            'status' => 'pending' // Fix: Set default status
        ]);

        return response()->json(['success' => true, 'report' => $report]);
    }

    public function recent()
    {
        // Fix: Only show current user's recent reports
        $userEmail = auth()->user()->email;
        $reports = WasteReport::where('user_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return response()->json($reports);
    }

    public function stats()
    {
        // Fix: Only show current user's stats
        $userEmail = auth()->user()->email;
        $total = WasteReport::where('user_email', $userEmail)->sum('amount');
        $mostType = WasteReport::select('waste_type', DB::raw('COUNT(*) as count'))
            ->where('user_email', $userEmail)
            ->groupBy('waste_type')
            ->orderByDesc('count')
            ->first();

        // Get waste breakdown by type for environmental impact calculations (USER SPECIFIC)
        $wasteByType = WasteReport::select('waste_type', DB::raw('SUM(amount) as total_amount'))
            ->where('user_email', $userEmail)
            ->groupBy('waste_type')
            ->pluck('total_amount', 'waste_type')
            ->toArray();

        // USER SPECIFIC Activity Metrics - Database Driven
        
        // Today's reports count (USER SPECIFIC)
        $todayReports = WasteReport::where('user_email', $userEmail)
            ->whereDate('created_at', today())
            ->count();
        
        // This week's reports for goal tracking (USER SPECIFIC)
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyReports = WasteReport::where('user_email', $userEmail)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();
        
        // Weekly goal (can be made configurable)
        $weeklyGoal = 10; // Individual user goal
        $weeklyProgress = min($weeklyReports, $weeklyGoal);
        
        // This month's total for monthly tracking (USER SPECIFIC)
        $monthlyReports = WasteReport::where('user_email', $userEmail)
            ->whereYear('created_at', now()->year)
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
        $userEmail = auth()->user()->email;
        
        // Today's statistics (user-specific)
        $today = today();
        $todayReports = WasteReport::where('user_email', $userEmail)
            ->whereDate('created_at', $today)->count();
        $todayWasteAmount = WasteReport::where('user_email', $userEmail)
            ->whereDate('created_at', $today)->sum('amount');
        
        // This week's statistics (user-specific)
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyReports = WasteReport::where('user_email', $userEmail)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        
        // User's unique locations reported today
        $activeContributors = WasteReport::where('user_email', $userEmail)
            ->whereDate('created_at', $today)
            ->distinct('location')
            ->count('location');
        
        // Hourly activity today for this user
        $hourlyActivity = WasteReport::where('user_email', $userEmail)
            ->whereDate('created_at', $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();
        
        // User's top locations by report count (last 7 days)
        $topContributors = WasteReport::where('user_email', $userEmail)
            ->select('location', DB::raw('COUNT(*) as report_count'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('location')
            ->orderByDesc('report_count')
            ->limit(5)
            ->get();
        
        // Daily trend for this user (last 7 days)
        $dailyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = WasteReport::where('user_email', $userEmail)
                ->whereDate('created_at', $date)->count();
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
            'weeklyGoal' => 10, // Individual user goal
            'hourlyActivity' => $hourlyActivity,
            'topContributors' => $topContributors,
            'dailyTrend' => $dailyTrend,
            'lastUpdate' => now()->toISOString()
        ]);
    }
}
