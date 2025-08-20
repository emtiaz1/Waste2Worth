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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
    
    private function getCoinTransactionHistory($userEmail, $limit = 5)
    {
        try {
            return Coin::where('user_email', $userEmail)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get(['reason', 'eco_coin_value', 'created_at'])
                ->map(function($coin) {
                    return [
                        'reason' => $coin->reason,
                        'amount' => $coin->eco_coin_value,
                        'date' => $coin->created_at->format('M j, Y'),
                        'time_ago' => $coin->created_at->diffForHumans()
                    ];
                });
        } catch (\Exception $e) {
            \Log::error("Error getting coin history for {$userEmail}: " . $e->getMessage());
            return collect([]);
        }
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
                
            // Calculate Eco Coins from the coins table
            $totalEcoCoins = 0;
            $monthlyCoins = 0;
            $weeklyCoins = 0;
            try {
                // Get total coins earned by the user
                $totalEcoCoins = Coin::where('user_email', $userEmail)->sum('eco_coin_value') ?? 0;
                
                // Get coins earned this month
                $monthlyCoins = Coin::where('user_email', $userEmail)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('eco_coin_value') ?? 0;
                    
                // Get coins earned this week
                $weeklyCoins = Coin::where('user_email', $userEmail)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('eco_coin_value') ?? 0;
                    
                \Log::info("Coins calculated for {$userEmail}: Total={$totalEcoCoins}, Monthly={$monthlyCoins}, Weekly={$weeklyCoins}");
            } catch (\Exception $e) {
                \Log::error("Error calculating coins for {$userEmail}: " . $e->getMessage());
                // Coins table might not exist or have issues
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
                'weekly_coins' => $weeklyCoins,
                'total_purchases' => $totalPurchases,
                'coins_spent' => $coinsSpent,
                'coins_available' => max(0, $totalEcoCoins - $coinsSpent) // Ensure non-negative
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
                
            $totalWasteReported = WasteReport::where('user_email', $userEmail)->sum('amount') ?? 0;
            
            // Get waste collected by user (as collector) - only confirmed collections
            $totalWasteCollected = WasteCollection::where('collector_email', $userEmail)
                ->whereIn('status', ['confirmed', 'completed', 'collected'])
                ->sum('actual_weight') ?? 0;
            
            $totalWasteImpact = $totalWasteReported + $totalWasteCollected;
            $carbonSaved = $this->calculateCarbonFootprintSaved($totalWasteImpact, $mostReportedType);
            
            $recentReports = WasteReport::where('user_email', $userEmail)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
                
            $todayReports = WasteReport::where('user_email', $userEmail)
                ->whereDate('created_at', today())
                ->count();
                
            $todayCollections = WasteCollection::where('collector_email', $userEmail)
                ->whereIn('status', ['confirmed', 'completed', 'collected'])
                ->whereDate('updated_at', today())
                ->count();
                
            return [
                'most_reported_type' => $mostReportedType ? $mostReportedType->waste_type : 'None',
                'total_waste_reported' => number_format($totalWasteReported, 1),
                'total_waste_collected' => number_format($totalWasteCollected, 1),
                'carbon_footprint_saved' => $carbonSaved,
                'recent_reports_30_days' => $recentReports,
                'today_reports' => $todayReports,
                'today_collections' => $todayCollections,
                'environmental_score' => $this->calculateEnvironmentalScore($totalWasteImpact, $recentReports)
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
                'available_collections' => $this->getAvailableCollections($userEmail), // Other users' waste reports
                'my_collection_requests' => $this->getMyCollectionRequests($userEmail), // Current user's collection assignments
                'collection_stats' => $this->getCollectionStats(),
                'recent_community_activity' => $this->getRecentCommunityActivity($userEmail),
                'ongoing_events' => $this->getOngoingEvents(),
                'global_impact' => $this->getGlobalImpact(),
                'recent_discussions' => $this->getRecentDiscussions(),
                'my_waste_reports' => $this->getMyWasteReports($userEmail), // Current user's reports
                'recent_coin_transactions' => $this->getCoinTransactionHistory($userEmail, 3) // Recent coin earnings
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
    
    private function getRecentCommunityActivity($currentUserEmail)
    {
        try {
            // Get today's date
            $today = Carbon::today();
            
            // Get all waste reports from today
            $todaysReports = WasteReport::select('id', 'waste_type', 'amount', 'location', 'user_email', 'created_at', 'status')
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            $activity = collect();

            foreach ($todaysReports as $report) {
                $reporterName = $this->getUsernameByEmail($report->user_email);
                
                // Get the latest collection status for this report
                $latestCollection = WasteCollection::where('waste_report_id', $report->id)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                // Determine current status and details
                $currentStatus = 'available';
                $statusMessage = "{$reporterName} reported {$report->amount}kg of {$report->waste_type}";
                $statusColor = 'primary';
                $statusIcon = 'fas fa-plus-circle';
                $collectorName = null;
                $latestTime = $report->created_at;

                if ($latestCollection) {
                    $collectorName = $this->getUsernameByEmail($latestCollection->collector_email);
                    
                    if ($latestCollection->status === 'collected' || $latestCollection->status === 'confirmed') {
                        $currentStatus = 'collected';
                        $actualWeight = $latestCollection->actual_weight ?: $report->amount;
                        $statusMessage = "{$collectorName} collected " . $actualWeight . "kg of {$report->waste_type} (reported by {$reporterName}) - ✅ Confirmed by admin";
                        $statusColor = 'success';
                        $statusIcon = 'fas fa-check-circle';
                        $latestTime = Carbon::parse($latestCollection->collected_at ?: $latestCollection->updated_at);
                    } elseif ($latestCollection->status === 'submitted') {
                        $currentStatus = 'submitted';
                        $actualWeight = $latestCollection->actual_weight ?: $report->amount;
                        $statusMessage = "{$collectorName} submitted collection of " . $actualWeight . "kg of {$report->waste_type} (reported by {$reporterName}) - ⏳ Pending admin confirmation";
                        $statusColor = 'info';
                        $statusIcon = 'fas fa-clock';
                        $latestTime = Carbon::parse($latestCollection->collected_at ?: $latestCollection->updated_at);
                    } elseif ($latestCollection->status === 'cancelled') {
                        $currentStatus = 'cancelled';
                        $statusMessage = "Collection cancelled for {$report->amount}kg of {$report->waste_type} (reported by {$reporterName})";
                        $statusColor = 'danger';
                        $statusIcon = 'fas fa-times-circle';
                        $latestTime = $latestCollection->updated_at;
                    } else {
                        // assigned status
                        $currentStatus = 'assigned';
                        $statusMessage = "{$collectorName} is assigned to collect {$report->amount}kg of {$report->waste_type} (reported by {$reporterName})";
                        $statusColor = 'warning';
                        $statusIcon = 'fas fa-user-check';
                        $latestTime = Carbon::parse($latestCollection->assigned_at ?: $latestCollection->created_at);
                    }
                }

                // Add single entry for this report with latest status
                $activity->push([
                    'id' => $report->id,
                    'waste_type' => $report->waste_type,
                    'amount' => $report->amount,
                    'location' => $report->location,
                    'reporter_email' => $report->user_email,
                    'reporter_name' => $reporterName,
                    'collector_name' => $collectorName,
                    'time' => $latestTime,
                    'time_ago' => $latestTime->diffForHumans(),
                    'status' => $currentStatus,
                    'message' => $statusMessage,
                    'icon' => $statusIcon,
                    'color' => $statusColor,
                    'can_collect' => ($currentStatus === 'available' && $report->user_email !== $currentUserEmail)
                ]);
            }

            // Sort by latest time and limit to 15 items
            return $activity->sortByDesc('time')->take(15)->values();

        } catch (\Exception $e) {
            \Log::error('Error getting recent community activity: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    private function getAvailableCollections($currentUserEmail)
    {
        try {
            // Get IDs of waste reports that are already assigned
            $assignedWasteIds = WasteCollection::where('status', '!=', 'cancelled')
                ->pluck('waste_report_id')
                ->toArray();

            return WasteReport::select('id', 'waste_type', 'amount', 'location', 'user_email', 'created_at', 'description')
                ->whereNotNull('user_email')
                ->where('user_email', '!=', $currentUserEmail) // Only show other users' reports
                ->whereIn('status', ['pending', 'reported']) // Available for collection
                ->whereNotIn('id', $assignedWasteIds) // Exclude already assigned reports
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($report) {
                    return [
                        'id' => $report->id,
                        'type' => $report->waste_type,
                        'amount' => $report->amount,
                        'location' => $report->location,
                        'description' => $report->description,
                        'reported_by' => $this->getUsernameByEmail($report->user_email),
                        'time_ago' => $report->created_at->diffForHumans(),
                        'priority' => $this->calculateCollectionPriority($report)
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function getMyCollectionRequests($userEmail)
    {
        try {
            return WasteCollection::where('collector_email', $userEmail)
                ->with('wasteReport')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($collection) {
                    return [
                        'id' => $collection->id,
                        'collection_id' => $collection->id, // For the submit button
                        'waste_report_id' => $collection->waste_report_id,
                        'status' => $collection->status,
                        'assigned_date' => $collection->created_at->format('M j, Y'),
                        'expected_weight' => $collection->expected_weight,
                        'actual_weight' => $collection->actual_weight,
                        'location' => $collection->wasteReport->location ?? 'N/A',
                        'waste_type' => $collection->wasteReport->waste_type ?? 'Unknown'
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function getMyWasteReports($userEmail)
    {
        try {
            return WasteReport::where('user_email', $userEmail)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($report) {
                    return [
                        'id' => $report->id,
                        'type' => $report->waste_type,
                        'amount' => $report->amount,
                        'location' => $report->location,
                        'status' => $report->status ?? 'pending',
                        'created_at' => $report->created_at->format('M j, Y'),
                        'time_ago' => $report->created_at->diffForHumans(),
                        'is_collected' => in_array($report->status, ['collected', 'completed'])
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    private function getUsernameByEmail($email)
    {
        try {
            $profile = Profile::where('email', $email)->first();
            return $profile ? $profile->username : 'Anonymous User';
        } catch (\Exception $e) {
            return 'Anonymous User';
        }
    }
    
    private function calculateCollectionPriority($report)
    {
        // Calculate priority based on waste type, amount, and age
        $priorities = [
            'Electronic' => 5,
            'Chemical' => 5,
            'Medical' => 4,
            'Plastic' => 3,
            'Metal' => 3,
            'Glass' => 2,
            'Paper' => 2,
            'Organic' => 1
        ];
        
        $typePriority = $priorities[$report->waste_type] ?? 2;
        $agePriority = $report->created_at->diffInDays() > 7 ? 2 : 1;
        $amountPriority = $report->amount > 10 ? 2 : 1;
        
        $totalPriority = $typePriority + $agePriority + $amountPriority;
        
        if ($totalPriority >= 7) return 'High';
        if ($totalPriority >= 5) return 'Medium';
        return 'Low';
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
            $todayCollected = 0;
            
            try {
                // Only include confirmed and completed collections (not submitted)
                $totalCollected = WasteCollection::whereIn('status', ['confirmed', 'completed', 'collected'])
                    ->sum('actual_weight') ?? 0;
                
                // Today's confirmed collections only
                $todayCollected = WasteCollection::whereIn('status', ['confirmed', 'completed', 'collected'])
                    ->whereDate('updated_at', today())
                    ->sum('actual_weight') ?? 0;
            } catch (\Exception $e) {
                // WasteCollection might not be available
            }
            
            return [
                'total_waste_reported' => number_format($totalWasteReported, 1),
                'total_reports' => $totalReports,
                'total_collected' => number_format($totalCollected, 1),
                'today_collected' => number_format($todayCollected, 1),
                'carbon_saved' => number_format(($totalWasteReported + $totalCollected) * 0.5, 1),
                'trees_equivalent' => number_format(($totalWasteReported + $totalCollected) * 0.02, 0),
            ];
        } catch (\Exception $e) {
            return [
                'total_waste_reported' => '0',
                'total_reports' => 0,
                'total_collected' => '0',
                'today_collected' => '0',
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
    
    // New method to handle collection requests
    public function requestCollection(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'waste_report_id' => 'required|integer|exists:wastereport,id'
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
            
            $wasteReportId = $request->input('waste_report_id');
            \Log::info('Collection request for waste report ID: ' . $wasteReportId . ' by user: ' . $user->email);
            
            $wasteReport = WasteReport::find($wasteReportId);
            
            if (!$wasteReport) {
                \Log::error('Waste report not found: ' . $wasteReportId);
                return response()->json(['error' => 'Waste report not found'], 404);
            }
            
            // Check if waste report has a status column and is still available
            if (isset($wasteReport->status) && $wasteReport->status !== 'pending') {
                \Log::info('Waste report already processed: ' . $wasteReportId . ' - Status: ' . $wasteReport->status);
                return response()->json(['error' => 'This waste report is no longer available for collection'], 400);
            }
            
            // Prevent users from collecting their own waste
            if ($wasteReport->user_email === $user->email) {
                \Log::info('User tried to collect own waste: ' . $user->email);
                return response()->json(['error' => 'Cannot collect your own waste report'], 400);
            }
            
            // Check if already assigned
            $existingCollection = WasteCollection::where('waste_report_id', $wasteReportId)
                ->where('status', '!=', 'cancelled')
                ->first();
                
            if ($existingCollection) {
                \Log::info('Waste already assigned: ' . $wasteReportId);
                return response()->json(['error' => 'This waste is already assigned for collection'], 400);
            }
            
            // Get collector profile information
            $collectorProfile = \App\Models\Profile::where('email', $user->email)->first();
            $collectorName = null;
            $collectorContact = null;
            
            if ($collectorProfile) {
                // Try to get full name first
                $firstName = trim($collectorProfile->first_name ?? '');
                $lastName = trim($collectorProfile->last_name ?? '');
                
                if ($firstName || $lastName) {
                    $collectorName = trim($firstName . ' ' . $lastName);
                } else {
                    // Fall back to username
                    $collectorName = $collectorProfile->username;
                }
                
                $collectorContact = $collectorProfile->phone;
            }
            
            // If profile info not available, use fallback from user model
            if (!$collectorName) {
                $collectorName = $user->name ?? 'Unknown User';
            }
            
            // Create collection request
            try {
                $collection = WasteCollection::create([
                    'waste_report_id' => $wasteReportId,
                    'requester_email' => $wasteReport->user_email, // Person who reported the waste
                    'collector_email' => $user->email, // Person who wants to collect it
                    'collector_name' => $collectorName,
                    'collector_contact' => $collectorContact,
                    'expected_weight' => $wasteReport->amount,
                    'status' => 'assigned',
                    'requested_at' => now(),
                    'assigned_at' => now(),
                    'assigned_date' => now()
                ]);
                \Log::info('Collection created with ID: ' . $collection->id . ' - Collector: ' . $collectorName);
            } catch (\Exception $collectionError) {
                \Log::error('Failed to create collection: ' . $collectionError->getMessage());
                return response()->json(['error' => 'Failed to create collection request'], 500);
            }
            
            // Update waste report status (if status column exists)
            try {
                if (Schema::hasColumn('wastereport', 'status')) {
                    $wasteReport->update(['status' => 'assigned']);
                    \Log::info('Waste report status updated to assigned');
                }
            } catch (\Exception $updateError) {
                \Log::error('Failed to update waste report status: ' . $updateError->getMessage());
                // Don't fail the request if status update fails
            }
            
            // Award 5 eco coins to the original reporter when someone requests to collect their waste
            try {
                \App\Models\Coin::create([
                    'user_email' => $wasteReport->user_email,
                    'reason' => 'Your waste report was assigned for collection - ' . $wasteReport->amount . 'kg ' . $wasteReport->waste_type,
                    'eco_coin_value' => 5
                ]);
                \Log::info('5 coins awarded to reporter: ' . $wasteReport->user_email);
            } catch (\Exception $coinError) {
                \Log::error('Failed to award coins: ' . $coinError->getMessage());
                // Don't fail the entire request if coin creation fails
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Collection request submitted successfully! You will earn 10 eco coins when you complete the collection.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Collection request failed: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function submitCollection(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                \Log::warning('Unauthorized collection submission attempt');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            \Log::info('Collection submission attempt by user: ' . $user->email);
            \Log::info('Request data: ' . json_encode($request->all()));

            // Validate request
            $request->validate([
                'collection_id' => 'required|exists:waste_collections,id',
                'actual_weight' => 'required|numeric|min:0',
                'collection_notes' => 'nullable|string|max:1000',
                'collection_photos.*' => 'nullable|image|max:2048'
            ]);

            $collectionId = $request->input('collection_id');
            $actualWeight = $request->input('actual_weight');
            $collectionNotes = $request->input('collection_notes');

            \Log::info('Collection submission for ID: ' . $collectionId . ' by user: ' . $user->email);

            // Find the collection and verify ownership
            $collection = WasteCollection::find($collectionId);
            
            if (!$collection) {
                return response()->json(['error' => 'Collection not found'], 404);
            }

            if ($collection->collector_email !== $user->email) {
                return response()->json(['error' => 'Unauthorized to submit this collection'], 403);
            }

            if ($collection->status !== 'assigned') {
                return response()->json(['error' => 'Collection is not in assigned status'], 400);
            }

            // Handle photo uploads
            $photosPaths = [];
            if ($request->hasFile('collection_photos')) {
                foreach ($request->file('collection_photos') as $photo) {
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('collection_photos', $filename, 'public');
                    $photosPaths[] = $path;
                }
            }

            // Update collection with submission data - set to pending confirmation
            $collection->update([
                'actual_weight' => $actualWeight,
                'collection_notes' => $collectionNotes,
                'collection_photos' => $photosPaths,
                'status' => 'submitted', // Changed from 'collected' to 'submitted'
                'collected_at' => now()
            ]);

            // Update the related waste report status to submitted (not collected yet)
            if ($collection->wasteReport) {
                $collection->wasteReport->update(['status' => 'submitted']);
            }

            // Don't award coins yet - will be awarded by admin upon confirmation
            
            \Log::info('Collection submitted successfully and pending admin confirmation for user: ' . $user->email);

            return response()->json([
                'success' => true, 
                'message' => 'Collection submitted successfully! Pending admin confirmation.',
                'coins_earned' => 0 // No coins until admin confirms
            ])->header('Content-Type', 'application/json');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Collection submission validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed', 
                'details' => $e->errors()
            ], 422)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            \Log::error('Collection submission failed: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500)->header('Content-Type', 'application/json');
        }
    }

    public function getCommunityActivity()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $userEmail = $user->email;
            $communityActivity = $this->getRecentCommunityActivity($userEmail);
            
            return response()->json([
                'success' => true,
                'activity' => $communityActivity,
                'timestamp' => now()->toISOString()
            ])->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            \Log::error('Community activity API error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load community activity'], 500)->header('Content-Type', 'application/json');
        }
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
            'total_waste_reported' => '0',
            'total_waste_collected' => '0',
            'carbon_footprint_saved' => '0',
            'recent_reports_30_days' => 0,
            'today_reports' => 0,
            'today_collections' => 0,
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
            'available_collections' => collect([]),
            'my_collection_requests' => collect([]),
            'collection_stats' => [
                'pending' => 0,
                'assigned' => 0,
                'collected' => 0,
                'completed' => 0,
                'total_weight_collected' => 0,
                'today_collections' => 0
            ],
            'recent_community_activity' => collect([]),
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
            'recent_discussions' => collect([]),
            'my_waste_reports' => collect([])
        ];
    }
}
