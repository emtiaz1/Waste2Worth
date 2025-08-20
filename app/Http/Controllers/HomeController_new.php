<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\WasteReport;
use App\Models\WasteCollection;
use App\Models\Coin;
use App\Models\Purchase;
use App\Models\ForumDiscussion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $profile = null;
            $dashboardData = [];
            
            if ($user) {
                $profile = Profile::where('email', $user->email)->first();
                
                if (!$profile) {
                    $profile = Profile::create([
                        'email' => $user->email,
                        'username' => $user->name ?? 'User',
                    ]);
                }
                
                try {
                    $profile = $this->enhanceProfileWithStatistics($profile);
                    $dashboardData = $this->getDashboardData($user->email);
                } catch (\Exception $e) {
                    $profile->personal_stats = $this->getDefaultPersonalStats();
                    $profile->waste_impact = $this->getDefaultWasteImpact();
                    $profile->community_rank = $this->getDefaultCommunityRank();
                    $dashboardData = $this->getDefaultDashboardData();
                }
            }
            
            return view('home', compact('profile', 'dashboardData'));
        } catch (\Exception $e) {
            return view('home', [
                'profile' => null, 
                'dashboardData' => $this->getDefaultDashboardData()
            ]);
        }
    }
    
    private function enhanceProfileWithStatistics($profile)
    {
        $userEmail = $profile->email;
        
        $personalStats = $this->getPersonalStatistics($userEmail);
        $profile->personal_stats = $personalStats;
        
        $wasteImpact = $this->getWasteImpactData($userEmail);
        $profile->waste_impact = $wasteImpact;
        
        $communityRank = $this->getCommunityRanking($userEmail);
        $profile->community_rank = $communityRank;
        
        return $profile;
    }
    
    private function getPersonalStatistics($userEmail)
    {
        try {
            $totalWasteReported = WasteReport::where('user_email', $userEmail)->sum('amount') ?? 0;
            $totalReports = WasteReport::where('user_email', $userEmail)->count();
            $weeklyReports = WasteReport::where('user_email', $userEmail)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
            $monthlyReports = WasteReport::where('user_email', $userEmail)
                ->whereMonth('created_at', now()->month)
                ->count();
                
            $totalEcoCoins = 0;
            $monthlyCoins = 0;
            try {
                $totalEcoCoins = Coin::where('user_email', $userEmail)->sum('eco_coin_value') ?? 0;
                $monthlyCoins = Coin::where('user_email', $userEmail)
                    ->whereMonth('created_at', now()->month)
                    ->sum('eco_coin_value') ?? 0;
            } catch (\Exception $e) {
                // Coins table might not exist
            }
                
            $totalPurchases = 0;
            $coinsSpent = 0;
            try {
                $totalPurchases = Purchase::where('email', $userEmail)->count();
                $coinsSpent = Purchase::where('email', $userEmail)->sum('eco_coins_spent') ?? 0;
            } catch (\Exception $e) {
                // Purchases table might not exist
            }
            
            return [
                'total_waste_reported' => number_format($totalWasteReported, 1),
                'total_reports' => $totalReports,
                'weekly_reports' => $weeklyReports,
                'monthly_reports' => $monthlyReports,
                'total_eco_coins' => $totalEcoCoins,
                'monthly_coins' => $monthlyCoins,
                'total_purchases' => $totalPurchases,
                'coins_spent' => $coinsSpent,
                'coins_available' => $totalEcoCoins - $coinsSpent
            ];
        } catch (\Exception $e) {
            return $this->getDefaultPersonalStats();
        }
    }
    
    private function getWasteImpactData($userEmail)
    {
        try {
            $mostReportedType = WasteReport::select('waste_type', DB::raw('COUNT(*) as count'))
                ->where('user_email', $userEmail)
                ->groupBy('waste_type')
                ->orderByDesc('count')
                ->first();
                
            $totalWaste = WasteReport::where('user_email', $userEmail)->sum('amount') ?? 0;
            $carbonSaved = $this->calculateCarbonFootprintSaved($totalWaste, $mostReportedType);
            
            $recentReports = WasteReport::where('user_email', $userEmail)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
                
            $todayReports = WasteReport::where('user_email', $userEmail)
                ->whereDate('created_at', today())
                ->count();
                
            return [
                'most_reported_type' => $mostReportedType ? $mostReportedType->waste_type : 'None',
                'carbon_footprint_saved' => $carbonSaved,
                'recent_reports_30_days' => $recentReports,
                'today_reports' => $todayReports,
                'environmental_score' => $this->calculateEnvironmentalScore($totalWaste, $recentReports)
            ];
        } catch (\Exception $e) {
            return $this->getDefaultWasteImpact();
        }
    }
    
    private function getCommunityRanking($userEmail)
    {
        try {
            $userTotalWaste = WasteReport::where('user_email', $userEmail)->sum('amount') ?? 0;
            $userReports = WasteReport::where('user_email', $userEmail)->count();
            $userScore = ($userTotalWaste * 2) + ($userReports * 10);
            
            $higherScoreUsers = DB::table('wastereport')
                ->select('user_email', DB::raw('(SUM(amount) * 2 + COUNT(*) * 10) as score'))
                ->whereNotNull('user_email')
                ->groupBy('user_email')
                ->having('score', '>', $userScore)
                ->count();
                
            $totalActiveUsers = DB::table('wastereport')
                ->whereNotNull('user_email')
                ->distinct('user_email')
                ->count();
                
            return [
                'rank' => $higherScoreUsers + 1,
                'total_users' => $totalActiveUsers,
                'percentile' => $totalActiveUsers > 0 ? round((1 - $higherScoreUsers / $totalActiveUsers) * 100) : 100
            ];
        } catch (\Exception $e) {
            return $this->getDefaultCommunityRank();
        }
    }
    
    private function getDashboardData($userEmail)
    {
        try {
            return [
                'community_stats' => $this->getCommunityStats(),
                'pending_collections' => $this->getPendingCollections(),
                'collection_stats' => $this->getCollectionStats(),
                'recent_activity' => $this->getRecentCommunityActivity(),
                'ongoing_events' => $this->getOngoingEvents(),
                'global_impact' => $this->getGlobalImpact(),
                'recent_discussions' => $this->getRecentDiscussions()
            ];
        } catch (\Exception $e) {
            return $this->getDefaultDashboardData();
        }
    }
    
    private function getCommunityStats()
    {
        try {
            return [
                'total_reports' => WasteReport::count(),
                'total_waste_reported' => number_format(WasteReport::sum('amount') ?? 0, 1),
                'active_users' => WasteReport::distinct('user_email')->whereNotNull('user_email')->count(),
                'today_reports' => WasteReport::whereDate('created_at', today())->count(),
                'this_week_reports' => WasteReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month_reports' => WasteReport::whereMonth('created_at', now()->month)->count()
            ];
        } catch (\Exception $e) {
            return [
                'total_reports' => 0,
                'total_waste_reported' => '0',
                'active_users' => 0,
                'today_reports' => 0,
                'this_week_reports' => 0,
                'this_month_reports' => 0
            ];
        }
    }
    
    private function getPendingCollections()
    {
        try {
            return WasteCollection::getPendingCollections(5);
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function getCollectionStats()
    {
        try {
            return WasteCollection::getCollectionStats();
        } catch (\Exception $e) {
            return [
                'pending' => 0,
                'assigned' => 0,
                'collected' => 0,
                'completed' => 0,
                'total_weight_collected' => 0,
                'today_collections' => 0
            ];
        }
    }
    
    private function getRecentCommunityActivity()
    {
        try {
            return WasteReport::select('id', 'waste_type', 'amount', 'location', 'user_email', 'created_at')
                ->whereNotNull('user_email')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($report) {
                    return [
                        'id' => $report->id,
                        'type' => $report->waste_type,
                        'amount' => $report->amount,
                        'location' => $report->location,
                        'user_email' => $report->user_email,
                        'time_ago' => $report->created_at->diffForHumans(),
                        'needs_collection' => true
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function getOngoingEvents()
    {
        return [
            [
                'name' => 'Community Cleanup Drive',
                'location' => 'Central Park',
                'date' => '2025-08-25',
                'participants' => 45,
                'status' => 'upcoming'
            ],
            [
                'name' => 'Recycling Workshop',
                'location' => 'Green Center',
                'date' => '2025-08-28',
                'participants' => 23,
                'status' => 'upcoming'
            ]
        ];
    }
    
    private function getGlobalImpact()
    {
        try {
            $totalWasteReported = WasteReport::sum('amount') ?? 0;
            $totalReports = WasteReport::count();
            $totalCollected = 0;
            
            try {
                $totalCollected = WasteCollection::where('status', 'completed')->sum('actual_weight') ?? 0;
            } catch (\Exception $e) {
                // WasteCollection might not be available
            }
            
            return [
                'total_waste_reported' => number_format($totalWasteReported, 1),
                'total_reports' => $totalReports,
                'total_collected' => number_format($totalCollected, 1),
                'carbon_saved' => number_format($totalWasteReported * 0.5, 1),
                'trees_equivalent' => number_format($totalWasteReported * 0.02, 0),
            ];
        } catch (\Exception $e) {
            return [
                'total_waste_reported' => '0',
                'total_reports' => 0,
                'total_collected' => '0',
                'carbon_saved' => '0',
                'trees_equivalent' => '0'
            ];
        }
    }
    
    private function getRecentDiscussions()
    {
        try {
            return ForumDiscussion::orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function($discussion) {
                    return [
                        'username' => $discussion->username,
                        'message' => Str::limit($discussion->message, 100),
                        'time_ago' => $discussion->created_at->diffForHumans()
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function calculateCarbonFootprintSaved($totalWaste, $mostReportedType)
    {
        $carbonFactors = [
            'Plastic' => 2.1,
            'Paper' => 0.9,
            'Glass' => 0.3,
            'Metal' => 3.2,
            'Organic' => 0.5,
            'Electronic' => 5.5,
            'Other' => 1.0
        ];
        
        $factor = $carbonFactors[$mostReportedType ? $mostReportedType->waste_type : 'Other'] ?? 1.0;
        return number_format($totalWaste * $factor, 1);
    }
    
    private function calculateEnvironmentalScore($totalWaste, $recentReports)
    {
        $wasteScore = min($totalWaste * 2, 50);
        $activityScore = min($recentReports * 5, 30);
        $consistencyScore = 20;
        
        return min($wasteScore + $activityScore + $consistencyScore, 100);
    }
    
    // Default data methods
    private function getDefaultPersonalStats()
    {
        return [
            'total_waste_reported' => '0',
            'total_reports' => 0,
            'weekly_reports' => 0,
            'monthly_reports' => 0,
            'total_eco_coins' => 0,
            'monthly_coins' => 0,
            'total_purchases' => 0,
            'coins_spent' => 0,
            'coins_available' => 0
        ];
    }
    
    private function getDefaultWasteImpact()
    {
        return [
            'most_reported_type' => 'None',
            'carbon_footprint_saved' => '0',
            'recent_reports_30_days' => 0,
            'today_reports' => 0,
            'environmental_score' => 0
        ];
    }
    
    private function getDefaultCommunityRank()
    {
        return [
            'rank' => 'N/A',
            'total_users' => 1,
            'percentile' => 0
        ];
    }
    
    private function getDefaultDashboardData()
    {
        return [
            'community_stats' => [
                'total_reports' => 0,
                'total_waste_reported' => '0',
                'active_users' => 0,
                'today_reports' => 0,
                'this_week_reports' => 0,
                'this_month_reports' => 0
            ],
            'pending_collections' => collect([]),
            'collection_stats' => [
                'pending' => 0,
                'assigned' => 0,
                'collected' => 0,
                'completed' => 0,
                'total_weight_collected' => 0,
                'today_collections' => 0
            ],
            'recent_activity' => collect([]),
            'ongoing_events' => [
                [
                    'name' => 'Community Cleanup Drive',
                    'location' => 'Central Park',
                    'date' => '2025-08-25',
                    'participants' => 0,
                    'status' => 'upcoming'
                ]
            ],
            'global_impact' => [
                'total_waste_reported' => '0',
                'total_reports' => 0,
                'total_collected' => '0',
                'carbon_saved' => '0',
                'trees_equivalent' => '0'
            ],
            'recent_discussions' => collect([])
        ];
    }
}
