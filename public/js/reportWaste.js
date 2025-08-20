document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wasteForm');
    const recentReports = document.getElementById('recentReports');
    const totalWaste = document.getElementById('totalWaste');
    const mostType = document.getElementById('mostType');
    
    // Community Activity elements
    const todayReports = document.getElementById('todayReports');
    const activeUsers = document.getElementById('activeUsers');
    const weeklyGoalFill = document.getElementById('weeklyGoalFill');
    const goalText = document.getElementById('goalText');
    const todayAmount = document.getElementById('todayAmount');
    const lastUpdate = document.getElementById('lastUpdate');

    // Note: Form submission is handled by Laravel, no need to prevent default
    // Just load initial data
    form.addEventListener('submit', function (e) {
        // Let Laravel handle the form submission naturally
        // No e.preventDefault() - let the form submit normally
    });

    // Fetch recent reports from database
    function loadReports() {
        fetch('/wastereport/recent')
            .then(res => res.json())
            .then(reports => {
                recentReports.innerHTML = '';
                if (reports.length === 0) {
                    recentReports.innerHTML = '<p>No reports yet.</p>';
                } else {
                    reports.forEach(report => {
                        recentReports.innerHTML += `
                            <div class="report-card">
                                <strong>${report.waste_type}</strong> - ${report.amount} ${report.unit}<br>
                                <em>${report.location}</em><br>
                                ${report.description ? `<p>${report.description}</p>` : ''}
                                ${report.image_path ? `<img src="/storage/${report.image_path}" style="max-width:100px;">` : ''}
                                <div class="muted">${new Date(report.created_at).toLocaleString()}</div>
                            </div>
                        `;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading reports:', error);
                recentReports.innerHTML = '<p>Error loading reports.</p>';
            });
    }

    // Fetch statistics from database
    function loadStats() {
        fetch('/wastereport/stats')
            .then(res => res.json())
            .then(stats => {
                totalWaste.textContent = stats.total + ' kg';
                mostType.textContent = stats.mostType;
                
                // Update community activity with real database data
                if (stats.communityActivity) {
                    updateCommunityActivity(stats.communityActivity);
                } else {
                    // Fallback for backward compatibility
                    updateCommunityActivity({
                        todayReports: 0,
                        activeContributors: 0,
                        weeklyReports: 0,
                        weeklyGoal: 50
                    });
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                totalWaste.textContent = '0 kg';
                mostType.textContent = 'None';
                resetCommunityActivity();
            });
    }

    // Load detailed community activity from dedicated endpoint
    function loadCommunityActivity() {
        fetch('/wastereport/community-activity')
            .then(res => res.json())
            .then(data => {
                updateCommunityActivity(data);
                console.log('Community activity loaded:', data);
            })
            .catch(error => {
                console.error('Error loading community activity:', error);
            });
    }

    // Update community activity display with real database data
    function updateCommunityActivity(data) {
        // Update displays with real database values
        todayReports.textContent = data.todayReports || 0;
        activeUsers.textContent = data.activeContributors || 0;
        todayAmount.textContent = (data.todayWasteAmount || 0) + ' kg';
        
        const weeklyProgress = data.weeklyReports || 0;
        const weeklyGoal = data.weeklyGoal || 50;
        const progressPercent = Math.min((weeklyProgress / weeklyGoal) * 100, 100);
        
        goalText.textContent = `${weeklyProgress}/${weeklyGoal} reports`;
        
        // Animate goal progress bar
        setTimeout(() => {
            weeklyGoalFill.style.width = progressPercent + '%';
        }, 300);
        
        // Update last update time
        if (data.lastUpdate) {
            const updateTime = new Date(data.lastUpdate);
            lastUpdate.textContent = `Last updated: ${updateTime.toLocaleTimeString()}`;
        } else {
            lastUpdate.textContent = 'Last updated: Just now';
        }
        
        // Add visual feedback for active data with animations
        animateCounterUpdate(todayReports, data.todayReports || 0);
        animateCounterUpdate(activeUsers, data.activeContributors || 0);
        
        if (data.todayReports > 0) {
            todayReports.style.color = '#2e7d32';
            todayReports.style.fontWeight = '700';
        }
        
        if (data.activeContributors > 0) {
            activeUsers.style.color = '#2e7d32';
            activeUsers.style.fontWeight = '700';
        }
    }
    
    // Animate counter updates
    function animateCounterUpdate(element, newValue) {
        element.style.transform = 'scale(1.2)';
        element.style.transition = 'transform 0.3s ease';
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 300);
    }

    // Reset community activity
    function resetCommunityActivity() {
        todayReports.textContent = '0';
        activeUsers.textContent = '0';
        todayAmount.textContent = '0 kg';
        goalText.textContent = '0/50 reports';
        weeklyGoalFill.style.width = '0%';
        lastUpdate.textContent = 'Last updated: Error loading data';
    }

    // Load data on page load
    loadReports();
    loadStats();
    loadCommunityActivity();
    
    // Auto-refresh community activity every 30 seconds for real-time updates
    setInterval(loadCommunityActivity, 30000);
});
