<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Coin;
use App\Models\WasteReport;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboardData = $this->getLeaderboardData();
        $recentAchievements = $this->getRecentAchievements();
        $statistics = $this->getStatistics();
        return view('leaderboard', compact('leaderboardData', 'recentAchievements', 'statistics'));
    }

    public function getLeaderboardData()
    {
        // Get comprehensive leaderboard data with real-time performance scores
        $leaderboard = DB::table('profiles as p')
            ->leftJoin('users as u', 'p.email', '=', 'u.email')
            ->leftJoin(DB::raw('(SELECT user_email, SUM(eco_coin_value) as total_coins FROM coins GROUP BY user_email) as c'), 'p.email', '=', 'c.user_email')
            ->leftJoin(DB::raw('(SELECT user_email, COUNT(*) as waste_reports_count, SUM(amount) as total_waste_amount FROM wastereport GROUP BY user_email) as w'), 'p.email', '=', 'w.user_email')
            ->leftJoin(DB::raw('(SELECT collector_email, COUNT(*) as collections_completed FROM waste_collections WHERE status = "completed" GROUP BY collector_email) as wc'), 'p.email', '=', 'wc.collector_email')
            ->select([
                'p.username',
                'p.first_name', 
                'p.last_name',
                'p.email',
                'p.location',
                'p.profile_picture',
                'p.points_earned',
                'p.contribution',
                'p.total_token',
                'p.achievements',
                'p.created_at',
                DB::raw('COALESCE(c.total_coins, 0) as eco_coins'),
                DB::raw('COALESCE(w.waste_reports_count, 0) as reports_count'),
                DB::raw('COALESCE(w.total_waste_amount, 0) as total_waste'),
                DB::raw('COALESCE(wc.collections_completed, 0) as collections_completed'),
                DB::raw('(
                    COALESCE(c.total_coins, 0) * 1.0 + 
                    COALESCE(w.waste_reports_count, 0) * 15.0 + 
                    COALESCE(w.total_waste_amount, 0) * 3.0 + 
                    COALESCE(wc.collections_completed, 0) * 20.0 +
                    COALESCE(p.points_earned, 0) * 1.0
                ) as performance_score')
            ])
            ->whereNotNull('p.username')
            ->orderByDesc('performance_score')
            ->limit(50)
            ->get();

        // Add rank to each user
        $leaderboard = $leaderboard->map(function ($user, $index) {
            $user->rank = $index + 1;
            $user->badge = $this->getBadge($user->rank, $user->performance_score);
            $user->display_name = $user->username ?: ($user->first_name . ' ' . $user->last_name);
            return $user;
        });

        return $leaderboard;
    }

    public function apiData()
    {
        $leaderboardData = $this->getLeaderboardData();
        
        // Get comprehensive real-time statistics
        $stats = [
            'total_participants' => Profile::whereNotNull('username')->count(),
            'total_eco_coins_distributed' => Coin::sum('eco_coin_value'),
            'total_waste_reports' => WasteReport::count(),
            'total_waste_collected' => WasteReport::sum('amount'),
            'active_collections' => \App\Models\WasteCollection::where('status', 'assigned')->count(),
            'completed_collections' => \App\Models\WasteCollection::where('status', 'completed')->count(),
            'pending_waste_reports' => WasteReport::whereIn('status', ['pending', 'reported'])->count(),
            'this_week_reports' => WasteReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_collections' => \App\Models\WasteCollection::where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'top_performer' => $leaderboardData->first(),
            'average_score' => round($leaderboardData->avg('performance_score'), 2),
            'total_weight_processed' => WasteReport::where('status', 'collected')->sum('amount'),
            'environmental_impact' => [
                'co2_saved' => round(WasteReport::where('status', 'collected')->sum('amount') * 0.5, 2), // kg of CO2 saved
                'trees_equivalent' => round(WasteReport::where('status', 'collected')->sum('amount') * 0.02, 0), // trees saved
                'recycling_rate' => WasteReport::where('status', 'collected')->count() > 0 
                    ? round((WasteReport::where('status', 'collected')->count() / WasteReport::count()) * 100, 1) 
                    : 0
            ]
        ];

        return response()->json([
            'leaderboard' => $leaderboardData,
            'statistics' => $stats,
            'last_updated' => now()->toISOString()
        ]);
    }

    private function getStatistics()
    {
        return [
            'total_participants' => Profile::whereNotNull('username')->count(),
            'total_eco_coins' => Coin::sum('eco_coin_value'),
            'total_waste_processed' => WasteReport::sum('amount'),
            'total_collections' => \App\Models\WasteCollection::where('status', 'completed')->count(),
            'active_users_today' => Profile::whereDate('updated_at', today())->count(),
            'environmental_impact' => [
                'co2_saved' => round(WasteReport::where('status', 'collected')->sum('amount') * 0.5, 2),
                'trees_equivalent' => round(WasteReport::where('status', 'collected')->sum('amount') * 0.02, 0),
            ]
        ];
    }

    private function getBadge($rank, $score)
    {
        if ($rank === 1) {
            return [
                'type' => 'gold',
                'icon' => 'fas fa-crown',
                'title' => 'Champion',
                'color' => '#FFD700'
            ];
        } elseif ($rank === 2) {
            return [
                'type' => 'silver',
                'icon' => 'fas fa-medal',
                'title' => 'Runner-up',
                'color' => '#C0C0C0'
            ];
        } elseif ($rank === 3) {
            return [
                'type' => 'bronze',
                'icon' => 'fas fa-medal',
                'title' => 'Third Place',
                'color' => '#CD7F32'
            ];
        } elseif ($rank <= 10) {
            return [
                'type' => 'top10',
                'icon' => 'fas fa-star',
                'title' => 'Top 10',
                'color' => '#4CAF50'
            ];
        } elseif ($score >= 100) {
            return [
                'type' => 'high_performer',
                'icon' => 'fas fa-fire',
                'title' => 'High Performer',
                'color' => '#FF5722'
            ];
        } else {
            return [
                'type' => 'participant',
                'icon' => 'fas fa-leaf',
                'title' => 'Eco Warrior',
                'color' => '#8BC34A'
            ];
        }
    }

    public function getUserRank($email)
    {
        $leaderboardData = $this->getLeaderboardData();
        $userRank = $leaderboardData->search(function ($user) use ($email) {
            return $user->email === $email;
        });

        return response()->json([
            'rank' => $userRank !== false ? $userRank + 1 : null,
            'user_data' => $userRank !== false ? $leaderboardData[$userRank] : null
        ]);
    }

    public function updatePerformanceScore($email)
    {
        // Recalculate and update performance score for specific user
        $userData = DB::table('profiles as p')
            ->leftJoin(DB::raw('(SELECT user_email, SUM(eco_coin_value) as total_coins FROM coins GROUP BY user_email) as c'), 'p.email', '=', 'c.user_email')
            ->leftJoin(DB::raw('(SELECT location, COUNT(*) as waste_reports_count, SUM(amount) as total_waste_amount FROM wastereport GROUP BY location) as w'), 'p.location', '=', 'w.location')
            ->select([
                DB::raw('(
                    COALESCE(c.total_coins, 0) * 0.4 + 
                    COALESCE(w.waste_reports_count, 0) * 10 + 
                    COALESCE(w.total_waste_amount, 0) * 2 + 
                    COALESCE(p.points_earned, 0) * 0.5
                ) as performance_score')
            ])
            ->where('p.email', $email)
            ->first();

        if ($userData) {
            Profile::where('email', $email)->update([
                'points_earned' => $userData->performance_score
            ]);
        }

        return response()->json(['success' => true, 'score' => $userData->performance_score ?? 0]);
    }

    public function getRecentAchievements()
    {
        $achievements = [];

        // 1. Recent Top Performer - User who gained most performance points in last 7 days
        $topPerformer = DB::table('profiles as p')
            ->leftJoin(DB::raw('(SELECT user_email, SUM(eco_coin_value) as total_coins FROM coins WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY user_email) as c'), 'p.email', '=', 'c.user_email')
            ->leftJoin(DB::raw('(SELECT location, COUNT(*) as recent_reports FROM wastereport WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY location) as w'), 'p.location', '=', 'w.location')
            ->select([
                'p.username', 'p.first_name', 'p.last_name', 'p.profile_picture', 'p.location',
                DB::raw('COALESCE(c.total_coins, 0) as recent_coins'),
                DB::raw('COALESCE(w.recent_reports, 0) as recent_reports'),
                DB::raw('(COALESCE(c.total_coins, 0) * 0.4 + COALESCE(w.recent_reports, 0) * 10) as recent_score')
            ])
            ->whereNotNull('p.username')
            ->having('recent_score', '>', 0)
            ->orderByDesc('recent_score')
            ->first();

        if ($topPerformer) {
            $achievements[] = [
                'type' => 'weekly_champion',
                'title' => 'Weekly Performance Champion',
                'description' => 'Gained ' . number_format($topPerformer->recent_score, 0) . ' performance points this week',
                'icon' => 'fas fa-chart-line',
                'color' => '#ff6b35',
                'user' => [
                    'name' => $topPerformer->username ?: ($topPerformer->first_name . ' ' . $topPerformer->last_name),
                    'avatar' => $topPerformer->profile_picture,
                    'location' => $topPerformer->location
                ],
                'stats' => [
                    'reports' => $topPerformer->recent_reports,
                    'coins' => $topPerformer->recent_coins
                ],
                'timestamp' => now()->subDays(rand(1, 6))
            ];
        }

        // 2. Waste Quantity Champion - User who reported most waste in last 30 days
        $wasteChampion = DB::table('profiles as p')
            ->join(DB::raw('(SELECT location, SUM(amount) as total_waste, COUNT(*) as report_count FROM wastereport WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY location) as w'), 'p.location', '=', 'w.location')
            ->select(['p.username', 'p.first_name', 'p.last_name', 'p.profile_picture', 'p.location', 'w.total_waste', 'w.report_count'])
            ->whereNotNull('p.username')
            ->orderByDesc('w.total_waste')
            ->first();

        if ($wasteChampion && $wasteChampion->total_waste > 10) {
            $achievements[] = [
                'type' => 'waste_champion',
                'title' => 'Environmental Impact Leader',
                'description' => 'Reported ' . number_format($wasteChampion->total_waste, 1) . 'kg of waste this month',
                'icon' => 'fas fa-leaf',
                'color' => '#4caf50',
                'user' => [
                    'name' => $wasteChampion->username ?: ($wasteChampion->first_name . ' ' . $wasteChampion->last_name),
                    'avatar' => $wasteChampion->profile_picture,
                    'location' => $wasteChampion->location
                ],
                'stats' => [
                    'waste_amount' => $wasteChampion->total_waste,
                    'reports' => $wasteChampion->report_count
                ],
                'timestamp' => now()->subDays(rand(7, 20))
            ];
        }

        // 3. Consistency Master - User with most consistent daily reporting
        $consistencyMaster = DB::table('profiles as p')
            ->join(DB::raw('(
                SELECT location, 
                COUNT(DISTINCT DATE(created_at)) as active_days,
                COUNT(*) as total_reports
                FROM wastereport 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) 
                GROUP BY location
                HAVING active_days >= 5
            ) as w'), 'p.location', '=', 'w.location')
            ->select(['p.username', 'p.first_name', 'p.last_name', 'p.profile_picture', 'p.location', 'w.active_days', 'w.total_reports'])
            ->whereNotNull('p.username')
            ->orderByDesc('w.active_days')
            ->first();

        if ($consistencyMaster) {
            $achievements[] = [
                'type' => 'consistency_master',
                'title' => 'Consistency Champion',
                'description' => 'Active for ' . $consistencyMaster->active_days . ' days in the past 2 weeks',
                'icon' => 'fas fa-calendar-check',
                'color' => '#2196f3',
                'user' => [
                    'name' => $consistencyMaster->username ?: ($consistencyMaster->first_name . ' ' . $consistencyMaster->last_name),
                    'avatar' => $consistencyMaster->profile_picture,
                    'location' => $consistencyMaster->location
                ],
                'stats' => [
                    'active_days' => $consistencyMaster->active_days,
                    'avg_per_day' => round($consistencyMaster->total_reports / $consistencyMaster->active_days, 1)
                ],
                'timestamp' => now()->subDays(rand(1, 10))
            ];
        }

        // 4. EcoCoin Collector - Recent high coin earner
        $coinCollector = DB::table('profiles as p')
            ->join(DB::raw('(SELECT user_email, SUM(eco_coin_value) as recent_coins, COUNT(*) as coin_transactions FROM coins WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) GROUP BY user_email) as c'), 'p.email', '=', 'c.user_email')
            ->select(['p.username', 'p.first_name', 'p.last_name', 'p.profile_picture', 'p.location', 'c.recent_coins', 'c.coin_transactions'])
            ->whereNotNull('p.username')
            ->where('c.recent_coins', '>=', 50)
            ->orderByDesc('c.recent_coins')
            ->first();

        if ($coinCollector) {
            $achievements[] = [
                'type' => 'coin_collector',
                'title' => 'EcoCoin Master',
                'description' => 'Earned ' . number_format($coinCollector->recent_coins) . ' EcoCoins in 2 weeks',
                'icon' => 'fas fa-coins',
                'color' => '#ffc107',
                'user' => [
                    'name' => $coinCollector->username ?: ($coinCollector->first_name . ' ' . $coinCollector->last_name),
                    'avatar' => $coinCollector->profile_picture,
                    'location' => $coinCollector->location
                ],
                'stats' => [
                    'coins' => $coinCollector->recent_coins,
                    'transactions' => $coinCollector->coin_transactions
                ],
                'timestamp' => now()->subDays(rand(3, 12))
            ];
        }

        // 5. Rising Star - New user with quick impact
        $risingStar = DB::table('profiles as p')
            ->join(DB::raw('(SELECT location, COUNT(*) as reports, SUM(amount) as total_waste FROM wastereport WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY location) as w'), 'p.location', '=', 'w.location')
            ->whereRaw('p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)')
            ->whereNotNull('p.username')
            ->where('w.reports', '>=', 3)
            ->select(['p.username', 'p.first_name', 'p.last_name', 'p.profile_picture', 'p.location', 'w.reports', 'w.total_waste', 'p.created_at'])
            ->orderByDesc('w.reports')
            ->first();

        if ($risingStar) {
            $daysSinceJoined = now()->diffInDays($risingStar->created_at);
            $achievements[] = [
                'type' => 'rising_star',
                'title' => 'Rising Star',
                'description' => 'Made strong impact with ' . $risingStar->reports . ' reports in just ' . $daysSinceJoined . ' days',
                'icon' => 'fas fa-star',
                'color' => '#9c27b0',
                'user' => [
                    'name' => $risingStar->username ?: ($risingStar->first_name . ' ' . $risingStar->last_name),
                    'avatar' => $risingStar->profile_picture,
                    'location' => $risingStar->location
                ],
                'stats' => [
                    'reports' => $risingStar->reports,
                    'days_active' => $daysSinceJoined
                ],
                'timestamp' => now()->subDays(rand(1, 5))
            ];
        }

        // Sort achievements by timestamp (most recent first) and limit to top 3
        return collect($achievements)->sortByDesc('timestamp')->take(3)->values()->all();
    }
}
