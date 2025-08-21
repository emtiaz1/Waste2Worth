<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteReport;
use App\Models\Profile;
use App\Models\WasteCollection;
use App\Models\Coin;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WasteReportController extends Controller
{
    // Show dashboard/homepage
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
            }

            // Get user email consistently
            $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';
            \Log::info('Dashboard loading for user email: ' . $userEmail);

            // Get or create user profile
            $profile = Profile::where('email', $userEmail)->first();
            if (!$profile) {
                $profile = Profile::create([
                    'email' => $userEmail,
                    'username' => $user->username ?? $user->name ?? 'User',
                    'contribution' => 0,
                    'total_token' => 0,
                ]);
            }

            // User's own waste reports
            $myReports = WasteReport::where('user_email', $userEmail)
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info('Found ' . $myReports->count() . ' reports for user: ' . $userEmail);

            // Available collections (pending waste reports from other users)
            $availableCollections = WasteReport::where('status', 'pending')
                ->where('user_email', '!=', $userEmail)
                ->orderBy('created_at', 'desc')
                ->get();
                
            \Log::info('Found ' . $availableCollections->count() . ' available collections for user: ' . $userEmail);

            // User's collection history (waste they collected from others)
            $myCollections = WasteCollection::where('requester_email', $userEmail)
                ->with(['wasteReport'])
                ->orderBy('created_at', 'desc')
                ->get();

            // User's coin data
            $totalCoins = Coin::getTotalCoinsForUser($userEmail);
            $recentEarnings = Coin::getRecentEarnings($userEmail, 5);
            $monthlyCoins = Coin::where('user_email', $userEmail)
                ->whereMonth('created_at', now()->month)
                ->sum('eco_coin_value');

            // Community stats
            $communityStats = [
                'total_reports' => WasteReport::count(),
                'total_waste_reported' => WasteReport::get()->sum(function ($report) {
                    $amount = $report->amount;
                    if (strtolower($report->unit ?? 'kg') === 'lbs') {
                        $amount = $amount * 0.453592; // Convert lbs to kg
                    }
                    return $amount;
                }),
                'active_users' => WasteReport::distinct('user_email')->count(),
                'today_reports' => WasteReport::whereDate('created_at', now())->count(),
                'this_week_reports' => WasteReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'total_collections' => WasteCollection::where('status', 'completed')->count(),
            ];

            // Recent community activity with detailed status tracking
            $recentActivity = WasteReport::whereDate('created_at', now())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) use ($userEmail) {
                    $isOwnReport = $item->user_email === $userEmail;
                    $reporterProfile = Profile::where('email', $item->user_email)->first();
                    
                    // Get collector information if the waste has been collected
                    $collection = WasteCollection::where('waste_report_id', $item->id)->first();
                    $collectorProfile = null;
                    if ($collection) {
                        $collectorProfile = Profile::where('email', $collection->requester_email)->first();
                    }
                    
                    // Create detailed status message
                    $detailedMessage = '';
                    $statusDetails = [];
                    
                    // Base report information
                    $reporterName = $reporterProfile->username ?? 'Unknown User';
                    $statusDetails[] = "📋 Reported by: {$reporterName}";
                    
                    if ($item->status === 'pending') {
                        $detailedMessage = "🔄 Waste report awaiting collection";
                        $statusDetails[] = "⏳ Status: Available for collection";
                    } elseif ($item->status === 'assigned' && $collection) {
                        $collectorName = $collectorProfile->username ?? 'Unknown Collector';
                        $detailedMessage = "👤 Collection assigned to volunteer";
                        $statusDetails[] = "📦 Assigned to: {$collectorName}";
                        $statusDetails[] = "⏰ Assigned: " . $collection->created_at->diffForHumans();
                    } elseif (($item->status === 'collected' && $collection && $collection->status !== 'completed') || ($item->status === 'collected' && !$collection)) {
                        $collectorName = $collectorProfile->username ?? 'Unknown Collector';
                        $detailedMessage = "✅ Successfully collected, awaiting admin verification";
                        $statusDetails[] = "📦 Collected by: {$collectorName}";
                        if ($collection && $collection->collected_at) {
                            $statusDetails[] = "✅ Collected: " . \Carbon\Carbon::parse($collection->collected_at)->diffForHumans();
                        }
                        $statusDetails[] = "⏳ Status: Pending admin verification for coin distribution";
                    } elseif ($item->status === 'confirmed' || ($collection && $collection->status === 'completed')) {
                        $collectorName = $collectorProfile->username ?? 'Unknown Collector';
                        $detailedMessage = "🎉 Collection verified and coins distributed!";
                        $statusDetails[] = "📦 Collected by: {$collectorName}";
                        $statusDetails[] = "✅ Confirmed by admin";
                        $statusDetails[] = "💰 Coins distributed to both reporter and collector";
                        if ($collection && $collection->collected_at) {
                            $statusDetails[] = "📅 Completed: " . \Carbon\Carbon::parse($collection->collected_at)->diffForHumans();
                        }
                    }
                    
                    return [
                        'id' => $item->id,
                        'status' => $item->status,
                        'icon' => ($item->status === 'confirmed' || ($collection && $collection->status === 'completed')) ? 'fas fa-check-circle' : ($item->status === 'collected' ? 'fas fa-hourglass-half' : ($item->status === 'assigned' ? 'fas fa-user-check' : 'fas fa-recycle')),
                        'color' => ($item->status === 'confirmed' || ($collection && $collection->status === 'completed')) ? 'success' : ($item->status === 'collected' ? 'warning' : ($item->status === 'assigned' ? 'info' : 'primary')),
                        'time_ago' => $item->created_at->diffForHumans(),
                        'message' => $detailedMessage,
                        'detailed_status' => $statusDetails,
                        'location' => $item->location,
                        'amount' => $item->amount,
                        'unit' => $item->unit,
                        'waste_type' => $item->waste_type,
                        'reported_by' => $item->user_email,
                        'reported_by_name' => $reporterName,
                        'collector_name' => $collectorProfile->username ?? null,
                        'collector_email' => $collection->requester_email ?? null,
                        'collection_status' => $collection ? $collection->status : null,
                        'is_own_report' => $isOwnReport,
                        'can_collect' => $item->status === 'pending' && !$isOwnReport,
                    ];
                });

            // Prepare dashboard data
            $dashboardData = [
                'my_waste_reports' => $myReports->map(function ($r) {
                    $collection = WasteCollection::where('waste_report_id', $r->id)->first();
                    return [
                        'id' => $r->id,
                        'type' => $r->waste_type,
                        'location' => $r->location,
                        'amount' => $r->amount,
                        'unit' => $r->unit,
                        'status' => $r->status,
                        'is_collected' => $r->status === 'collected',
                        'time_ago' => $r->created_at->diffForHumans(),
                        'description' => $r->description,
                        'collector_info' => $collection ? [
                            'collector_name' => $collection->collector_name,
                            'collected_at' => $collection->collected_at,
                            'actual_weight' => $collection->actual_weight,
                        ] : null,
                    ];
                }),
                'available_collections' => $availableCollections->map(function ($r) {
                    $reporterProfile = Profile::where('email', $r->user_email)->first();
                    return [
                        'id' => $r->id,
                        'type' => $r->waste_type,
                        'location' => $r->location,
                        'amount' => $r->amount,
                        'unit' => $r->unit,
                        'priority' => $r->amount > 10 ? 'High' : ($r->amount > 5 ? 'Medium' : 'Low'),
                        'reported_by' => $r->user_email,
                        'reported_by_name' => $reporterProfile->username ?? 'Unknown User',
                        'time_ago' => $r->created_at->diffForHumans(),
                        'description' => $r->description,
                    ];
                }),
                'my_collections' => $myCollections->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'waste_type' => $c->wasteReport->waste_type ?? 'Unknown',
                        'location' => $c->wasteReport->location ?? 'Unknown',
                        'amount_collected' => $c->actual_weight ?? $c->estimated_weight,
                        'status' => $c->status,
                        'collected_at' => $c->collected_at ? $c->collected_at->format('M j, Y') : null,
                        'time_ago' => $c->created_at->diffForHumans(),
                    ];
                }),
                // Add collection requests for the view (same data, different key for compatibility)
                'my_collection_requests' => $myCollections->map(function ($c) {
                    return [
                        'collection_id' => $c->id,
                        'waste_type' => $c->wasteReport->waste_type ?? 'Unknown',
                        'location' => $c->wasteReport->location ?? 'Unknown',
                        'expected_weight' => $c->estimated_weight,
                        'actual_weight' => $c->actual_weight,
                        'status' => $c->status,
                        'assigned_date' => $c->requested_at ? $c->requested_at->format('M j, Y') : 'N/A',
                        'submitted_date' => $c->collected_at ? $c->collected_at->format('M j, Y') : null,
                        'time_ago' => $c->created_at->diffForHumans(),
                    ];
                }),
                'community_stats' => $communityStats,
                'recent_community_activity' => $recentActivity,
                // Upcoming events from database
                'ongoing_events' => $this->getUpcomingEvents(),
                // Add global impact calculations
                'global_impact' => [
                    'total_waste_reported' => WasteReport::get()->sum(function ($report) {
                        $amount = $report->amount;
                        if (strtolower($report->unit ?? 'kg') === 'lbs') {
                            $amount = $amount * 0.453592; // Convert lbs to kg
                        }
                        return $amount;
                    }),
                    'total_collected' => WasteCollection::where('status', 'completed')->sum('actual_weight') ?: 0,
                    'today_collected' => WasteCollection::where('status', 'completed')
                        ->whereDate('collected_at', now())
                        ->sum('actual_weight') ?: 0,
                    'carbon_saved' => (WasteCollection::where('status', 'completed')->sum('actual_weight') ?: 0) * 2.5,
                    'trees_equivalent' => round((WasteCollection::where('status', 'completed')->sum('actual_weight') ?: 0) / 20), // 20kg waste = 1 tree equivalent
                ],
            ];

            // Update profile stats from actual data
            $userWasteCollected = $myCollections->where('status', 'completed')->sum('actual_weight') ?: 0;
            $profile->username = $profile->username ?: ($user->username ?? $user->name ?? 'User');
            $profile->waste_reports_count = $myReports->count();
            $profile->contribution = $myCollections->where('status', 'completed')->count();
            $profile->total_token = $totalCoins;
            $profile->points_earned = $totalCoins;
            $profile->carbon_footprint_saved = $userWasteCollected * 2.5; // 2.5kg CO2 saved per kg waste collected
            $profile->save();

            // Create profile object for view
            $profileData = (object)[
                'username' => $profile->username,
                'email' => $userEmail,
                'profile_picture' => $profile->profile_picture,
                'status' => $profile->status ?: 'Active Environmental Contributor',
                'personal_stats' => [
                    'total_reports' => $myReports->count(),
                    'weekly_reports' => $myReports->where('created_at', '>=', now()->startOfWeek())->count(),
                    'total_waste_reported' => $myReports->sum('amount') ?: 0,
                    'coins_available' => $totalCoins,
                    'monthly_coins' => $monthlyCoins,
                    'total_eco_coins' => $totalCoins,
                    'coins_spent' => 0, // Could be calculated from purchases table
                    'collections_completed' => $myCollections->where('status', 'completed')->count(),
                ],
                'community_rank' => $this->getUserRank($userEmail),
                'waste_impact' => [
                    'total_waste_collected' => $userWasteCollected,
                    'carbon_footprint_saved' => $profile->carbon_footprint_saved,
                    'trees_equivalent' => round($userWasteCollected / 20), // 20kg waste = 1 tree equivalent
                    'today_reports' => $myReports->where('created_at', '>=', now()->startOfDay())->count(),
                    'today_collections' => $myCollections->where('collected_at', '>=', now()->startOfDay())->count(),
                ],
                'recent_earnings' => $recentEarnings->map(function ($earning) {
                    return [
                        'reason' => $earning->reason,
                        'amount' => $earning->eco_coin_value,
                        'date' => $earning->created_at->format('M j'),
                    ];
                }),
            ];

            \Log::info('Dashboard data prepared successfully for user: ' . $userEmail);
            return view('home', compact('profileData', 'dashboardData'));
            
        } catch (\Exception $e) {
            \Log::error('Error in WasteReportController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return error view with empty data
            $profileData = $this->getEmptyProfileData();
            $dashboardData = $this->getEmptyDashboardData();
            
            return view('home', compact('profileData', 'dashboardData'))->withErrors(['error' => 'There was an issue loading the dashboard data: ' . $e->getMessage()]);
        }
    }

    // Helper method to get user rank with accurate percentile
    private function getUserRank($userEmail)
    {
        // Get all users with their total coins, ordered by highest coins
        $userRanks = Profile::select('email')
            ->selectRaw('(SELECT SUM(eco_coin_value) FROM coins WHERE coins.user_email = profiles.email) as total_coins')
            ->orderBy('total_coins', 'desc')
            ->pluck('email')
            ->toArray();
        
        $totalUsers = count($userRanks);
        $rank = array_search($userEmail, $userRanks);
        
        if ($rank === false) {
            return ['rank' => $totalUsers + 1, 'percentile' => 0];
        }
        
        $actualRank = $rank + 1;
        $percentile = max(0, round((($totalUsers - $actualRank) / max(1, $totalUsers)) * 100));
        
        return ['rank' => $actualRank, 'percentile' => $percentile];
    }

    // Helper method for empty profile data
    private function getEmptyProfileData()
    {
        return (object)[
            'username' => 'User',
            'email' => 'unknown@example.com',
            'profile_picture' => null,
            'status' => 'Active Environmental Contributor',
            'personal_stats' => [
                'total_reports' => 0, 'weekly_reports' => 0, 'total_waste_reported' => 0,
                'coins_available' => 0, 'monthly_coins' => 0, 'total_eco_coins' => 0, 'coins_spent' => 0,
                'collections_completed' => 0
            ],
            'community_rank' => ['rank' => 1, 'percentile' => 90],
            'waste_impact' => ['total_waste_collected' => 0, 'carbon_footprint_saved' => 0, 'today_reports' => 0, 'today_collections' => 0],
            'recent_earnings' => [],
        ];
    }

    // Helper method for empty dashboard data
    private function getEmptyDashboardData()
    {
        return [
            'my_waste_reports' => collect([]),
            'available_collections' => collect([]),
            'my_collections' => collect([]),
            'community_stats' => ['total_reports' => 0, 'total_waste_reported' => 0, 'active_users' => 0, 'today_reports' => 0, 'this_week_reports' => 0, 'total_collections' => 0],
            'recent_community_activity' => collect([]),
            'ongoing_events' => [], // Empty events array for error states
        ];
    }

    // Helper method to get upcoming events from database
    private function getUpcomingEvents()
    {
        try {
            // Get upcoming events (events from today onwards)
            $upcomingEvents = Event::where('date', '>=', now()->toDateString())
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->limit(5) // Limit to 5 upcoming events
                ->get();
                
            return $upcomingEvents->map(function ($event) {
                // Count participants - since EventRegistration model doesn't exist,
                // use a realistic random number for display
                $participants = rand(15, 50);
                
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'date' => $event->date,
                    'time' => $event->time,
                    'location' => $event->location,
                    'description' => $event->description,
                    'participants' => $participants,
                    'image' => $event->image,
                    'formatted_date' => \Carbon\Carbon::parse($event->date)->format('M j, Y'),
                    'days_until' => \Carbon\Carbon::parse($event->date)->diffInDays(now()),
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            \Log::error('Error fetching upcoming events: ' . $e->getMessage());
            
            // Return empty array if there's an error
            return [];
        }
    }

    // Show report waste form
    public function create()
    {
        return view('reportWaste');
    }

    // Store new waste report
    public function store(Request $request)
    {
        \Log::info('Waste report submission received', [
            'method' => $request->method(),
            'ajax' => $request->ajax(),
            'json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'user_authenticated' => auth()->check(),
            'data' => $request->except(['_token', 'image'])
        ]);
        
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                $errorMessage = 'Please login to submit a waste report.';
                \Log::warning('Unauthenticated waste report submission attempt');
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 401);
                }
                
                return redirect()->route('login')->with('error', $errorMessage);
            }

            $request->validate([
                'waste_type' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0.01',
                'unit' => 'required|string|max:10',
                'location' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);

            $user = auth()->user();
            $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';

            // Prepare waste report data
            $data = $request->only(['waste_type', 'amount', 'unit', 'location', 'description']);
            $data['user_email'] = $userEmail;
            $data['status'] = 'pending';

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('waste_images', 'public');
            }

            // Create waste report
            \Log::info('Creating waste report with data:', $data);
            $wasteReport = WasteReport::create($data);
            \Log::info('Waste report created successfully with ID: ' . $wasteReport->id);

            // Note: Eco-coins will be awarded only after someone collects the waste and admin confirms
            // No immediate coins for reporting

            // Update user profile stats
            $profile = Profile::where('email', $userEmail)->first();
            if ($profile) {
                $profile->waste_reports_count = WasteReport::where('user_email', $userEmail)->count();
                $profile->total_token = Coin::getTotalCoinsForUser($userEmail); // This will be 0 until collection is confirmed
                $profile->save();
            }

            $successMessage = "Waste report submitted successfully! Coins will be awarded after collection and admin verification.";
            
            // Check if request expects JSON (AJAX)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'report_id' => $wasteReport->id
                ]);
            }
            
            // Fallback for regular form submission
            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . json_encode($e->errors()));
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors())),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating waste report: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit report: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Failed to submit report: ' . $e->getMessage()])->withInput();
        }
    }

    // Calculate eco-coins based on waste amount and type
    private function calculateCoinsForWasteReport($amount, $wasteType)
    {
        // User gets 5 eco-coins per kg for reporting waste
        $coinsPerKg = 5;
        return (int) ceil($amount * $coinsPerKg);
    }

    // AJAX: Request collection
    public function requestCollection(Request $request)
    {
        try {
            $request->validate(['waste_report_id' => 'required|integer|exists:wastereport,id']);
            
            $user = auth()->user();
            $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';
            $wasteReport = WasteReport::find($request->waste_report_id);

            if ($wasteReport->status !== 'pending') {
                return response()->json(['success' => false, 'error' => 'This waste report is no longer available for collection']);
            }

            if ($wasteReport->user_email === $userEmail) {
                return response()->json(['success' => false, 'error' => 'You cannot collect your own waste report']);
            }

            // Check if user already requested this collection
            $existingRequest = WasteCollection::where('waste_report_id', $wasteReport->id)
                ->where('requester_email', $userEmail)
                ->first();

            if ($existingRequest) {
                return response()->json(['success' => false, 'error' => 'You have already requested this collection']);
            }

            // Create collection request
            $collection = WasteCollection::create([
                'waste_report_id' => $wasteReport->id,
                'requester_email' => $userEmail,
                'status' => 'pending',
                'requested_at' => now(),
                'estimated_weight' => $wasteReport->amount,
            ]);

            // Update waste report status
            $wasteReport->status = 'assigned';
            $wasteReport->save();

            \Log::info("User {$userEmail} requested collection for waste report {$wasteReport->id}");

            return response()->json([
                'success' => true, 
                'message' => 'Collection request submitted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in requestCollection: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to request collection']);
        }
    }

    // AJAX: Submit collection (when waste is actually collected)
    public function submitCollection(Request $request)
    {
        try {
            $request->validate([
                'collection_id' => 'required|integer|exists:waste_collections,id',
                'actual_weight' => 'required|numeric|min:0.01',
                'collection_photos.*' => 'nullable|image|max:2048',
                'collection_notes' => 'nullable|string',
            ]);

            $user = auth()->user();
            $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';
            $collection = WasteCollection::with('wasteReport')->find($request->collection_id);

            if ($collection->requester_email !== $userEmail) {
                return response()->json(['success' => false, 'error' => 'Unauthorized collection submission']);
            }

            if ($collection->status !== 'pending') {
                return response()->json(['success' => false, 'error' => 'This collection has already been processed']);
            }

            // Handle photo uploads
            $photoPaths = [];
            if ($request->hasFile('collection_photos')) {
                foreach ($request->file('collection_photos') as $photo) {
                    $photoPaths[] = $photo->store('collection_photos', 'public');
                }
            }

            // Update collection record
            $collection->update([
                'status' => 'submitted', // Changed from 'completed' to 'submitted' - awaiting admin verification
                'actual_weight' => $request->actual_weight,
                'collected_at' => now(),
                'collection_notes' => $request->collection_notes,
                'collection_photos' => json_encode($photoPaths),
            ]);

            // Update waste report status to 'collected'
            $collection->wasteReport->update(['status' => 'collected']);

            // Note: Coins will be awarded only after admin verification
            // This happens in AdminController@confirmCollection

            // Update collector's profile stats (collections count, but not coins yet)
            $profile = Profile::where('email', $userEmail)->first();
            if ($profile) {
                // Count all collections (submitted + completed)
                $profile->contribution = WasteCollection::where('requester_email', $userEmail)
                    ->whereIn('status', ['submitted', 'completed'])->count();
                // Coins will be updated after admin verification
                $profile->save();
            }

            return response()->json([
                'success' => true, 
                'message' => "Collection submitted successfully! Your submission is awaiting admin verification."
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in submitCollection: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to submit collection']);
        }
    }

    // Calculate coins for collecting waste
    private function calculateCoinsForCollection($actualWeight, $wasteType)
    {
        // User gets 10 eco-coins per kg for collecting waste
        $coinsPerKg = 10;
        return (int) ceil($actualWeight * $coinsPerKg);
    }

    // AJAX: Get dashboard data (refresh dashboard sections)
    public function getDashboardApiData()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'User not authenticated']);
            }

            $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';

            // Get fresh data
            $myReports = WasteReport::where('user_email', $userEmail)->orderBy('created_at', 'desc')->get();
            $availableCollections = WasteReport::where('status', 'pending')
                ->where('user_email', '!=', $userEmail)
                ->orderBy('created_at', 'desc')
                ->get();

            $communityStats = [
                'total_reports' => WasteReport::count(),
                'total_waste_reported' => WasteReport::sum('amount') ?: 0,
                'active_users' => WasteReport::distinct('user_email')->count(),
                'today_reports' => WasteReport::whereDate('created_at', now())->count(),
                'this_week_reports' => WasteReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ];

            $dashboardData = [
                'my_waste_reports' => $myReports->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'type' => $r->waste_type,
                        'location' => $r->location,
                        'amount' => $r->amount,
                        'unit' => $r->unit,
                        'status' => $r->status,
                        'is_collected' => $r->status === 'collected',
                        'time_ago' => $r->created_at->diffForHumans(),
                        'description' => $r->description,
                    ];
                }),
                'available_collections' => $availableCollections->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'type' => $r->waste_type,
                        'location' => $r->location,
                        'amount' => $r->amount,
                        'unit' => $r->unit,
                        'priority' => 'High',
                        'reported_by' => $r->user_email,
                        'time_ago' => $r->created_at->diffForHumans(),
                    ];
                }),
                'community_stats' => $communityStats,
            ];

            return response()->json(['success' => true, 'data' => $dashboardData]);
        } catch (\Exception $e) {
            \Log::error('Error in getDashboardApiData: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to load dashboard data']);
        }
    }

    // AJAX: Community activity feed
    public function communityActivity()
    {
        $recentActivity = WasteReport::whereDate('created_at', now())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'icon' => 'fas fa-recycle',
                    'color' => $item->status === 'pending' ? 'primary' : ($item->status === 'collected' ? 'success' : 'warning'),
                    'time_ago' => $item->created_at->diffForHumans(),
                    'message' => $item->description ?? '',
                    'location' => $item->location,
                    'amount' => $item->amount,
                    'waste_type' => $item->waste_type,
                    'collector_name' => null,
                    'can_collect' => $item->status === 'pending',
                ];
            });

        return response()->json(['success' => true, 'activity' => $recentActivity]);
    }

    // AJAX: Get recent reports for reportWaste page
    public function getRecentReports()
    {
        $user = auth()->user();
        $userEmail = $user->email ?? $user->username ?? 'unknown@example.com';
        
        $reports = WasteReport::where('user_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'waste_type' => $report->waste_type,
                    'amount' => $report->amount,
                    'unit' => $report->unit,
                    'location' => $report->location,
                    'description' => $report->description,
                    'image_path' => $report->image_path,
                    'status' => $report->status,
                    'created_at' => $report->created_at,
                ];
            });

        return response()->json($reports);
    }

    // AJAX: Get waste statistics for reportWaste page
    public function getWasteStats()
    {
        // Calculate total waste in kg (convert lbs to kg if needed)
        $totalWasteKg = WasteReport::get()->sum(function ($report) {
            $amount = $report->amount;
            // Convert lbs to kg if needed (1 lb = 0.453592 kg)
            if (strtolower($report->unit) === 'lbs') {
                $amount = $amount * 0.453592;
            }
            return $amount;
        });

        $mostType = WasteReport::selectRaw('waste_type, COUNT(*) as count')
            ->groupBy('waste_type')
            ->orderBy('count', 'desc')
            ->first();

        return response()->json([
            'total' => round($totalWasteKg, 2),
            'mostType' => $mostType ? $mostType->waste_type : 'None',
        ]);
    }

    // AJAX: Get community activity data for reportWaste page
    public function getCommunityActivity()
    {
        $todayReports = WasteReport::whereDate('created_at', now())->count();
        $activeUsers = WasteReport::distinct('user_email')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();
        $thisWeekReports = WasteReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        // Calculate today's waste amount in kg (convert lbs to kg if needed)
        $todayWasteKg = WasteReport::whereDate('created_at', now())->get()->sum(function ($report) {
            $amount = $report->amount;
            // Convert lbs to kg if needed (1 lb = 0.453592 kg)
            if (strtolower($report->unit) === 'lbs') {
                $amount = $amount * 0.453592;
            }
            return $amount;
        });

        return response()->json([
            'todayReports' => $todayReports,
            'activeContributors' => $activeUsers,
            'weeklyReports' => $thisWeekReports,
            'weeklyGoal' => 50,
            'todayWasteAmount' => round($todayWasteKg, 2),
            'lastUpdate' => now()->toISOString(),
        ]);
    }
}
