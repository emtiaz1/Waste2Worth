document.addEventListener('DOMContentLoaded', function () {
    const totalParticipants = document.getElementById('totalParticipants');
    const totalCoins = document.getElementById('totalCoins');
    const lastUpdate = document.getElementById('lastUpdate');
    const leaderboardTableBody = document.getElementById('leaderboardTableBody');

    // Load initial leaderboard data
    loadLeaderboardData();

    // Auto-refresh every 60 seconds
    setInterval(loadLeaderboardData, 60000);

    // Refresh leaderboard data from API
    async function loadLeaderboardData() {
        try {
            const response = await fetch('/leaderboard/api');
            const data = await response.json();
            
            updateLeaderboardDisplay(data);
            updateStatistics(data.statistics);
            updateLastUpdateTime(data.last_updated);
            
        } catch (error) {
            console.error('Error loading leaderboard data:', error);
            showError('Failed to load leaderboard data');
        }
    }

    // Update leaderboard table display
    function updateLeaderboardDisplay(data) {
        if (!data.leaderboard || data.leaderboard.length === 0) {
            return;
        }

        // Update individual rows if they exist (for live updates)
        data.leaderboard.forEach(user => {
            const existingRow = document.querySelector(`[data-rank="${user.rank}"]`);
            if (existingRow) {
                updateRowData(existingRow, user);
            }
        });

        // Add animation to updated scores
        animateScoreUpdates();
    }

    // Update row data with new user information
    function updateRowData(row, user) {
        const performanceScore = row.querySelector('.performance-score');
        const ecoCoins = row.querySelector('.eco-coins');
        
        if (performanceScore && performanceScore.textContent !== user.performance_score.toLocaleString()) {
            performanceScore.textContent = Math.round(user.performance_score).toLocaleString();
            performanceScore.classList.add('updated');
        }
        
        if (ecoCoins && ecoCoins.textContent !== user.eco_coins.toLocaleString()) {
            ecoCoins.textContent = user.eco_coins.toLocaleString();
            ecoCoins.classList.add('updated');
        }
    }

    // Update statistics display
    function updateStatistics(stats) {
        if (stats) {
            if (totalParticipants) {
                animateValue(totalParticipants, stats.total_participants);
            }
            if (totalCoins) {
                animateValue(totalCoins, stats.total_eco_coins_distributed);
            }
        }
    }

    // Update last update time
    function updateLastUpdateTime(timestamp) {
        if (lastUpdate && timestamp) {
            const updateTime = new Date(timestamp);
            lastUpdate.textContent = updateTime.toLocaleString();
        }
    }

    // Animate value changes
    function animateValue(element, newValue) {
        const currentValue = parseInt(element.textContent.replace(/,/g, '')) || 0;
        
        if (currentValue === newValue) return;
        
        const duration = 1000;
        const startTime = Date.now();
        const difference = newValue - currentValue;
        
        function updateValue() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = Math.round(currentValue + (difference * easeOutQuart));
            
            element.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(updateValue);
            }
        }
        
        requestAnimationFrame(updateValue);
    }

    // Animate score updates
    function animateScoreUpdates() {
        const updatedElements = document.querySelectorAll('.updated');
        updatedElements.forEach(element => {
            element.style.transform = 'scale(1.1)';
            element.style.backgroundColor = '#4caf50';
            element.style.color = 'white';
            element.style.borderRadius = '4px';
            element.style.padding = '2px 6px';
            element.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.backgroundColor = '';
                element.style.color = '';
                element.style.borderRadius = '';
                element.style.padding = '';
                element.classList.remove('updated');
            }, 1000);
        });
    }

    // Show error message
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <span>${message}</span>
        `;
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f44336;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            document.body.removeChild(errorDiv);
        }, 5000);
    }

    // Get current user's rank
    async function getCurrentUserRank() {
        try {
            const response = await fetch('/leaderboard/user-rank');
            const data = await response.json();
            
            if (data.rank) {
                highlightCurrentUser(data.rank);
                showUserRankNotification(data.rank, data.user_data);
            }
        } catch (error) {
            console.error('Error getting user rank:', error);
        }
    }

    // Highlight current user's row
    function highlightCurrentUser(rank) {
        const userRow = document.querySelector(`[data-rank="${rank}"]`);
        if (userRow) {
            userRow.classList.add('current-user');
            userRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Show user rank notification
    function showUserRankNotification(rank, userData) {
        const notification = document.createElement('div');
        notification.className = 'user-rank-notification';
        notification.innerHTML = `
            <div class="rank-info">
                <i class="fas fa-star"></i>
                <div>
                    <h4>Your Current Rank</h4>
                    <p><strong>#${rank}</strong> out of ${document.querySelectorAll('.leaderboard-row').length} participants</p>
                    <small>Score: ${Math.round(userData.performance_score).toLocaleString()} points</small>
                </div>
            </div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4caf50, #66bb6a);
            color: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            z-index: 1000;
            min-width: 250px;
            animation: slideUp 0.4s ease-out;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideDown 0.4s ease-in forwards';
            setTimeout(() => document.body.removeChild(notification), 400);
        }, 8000);
    }

    // Load user rank on page load
    getCurrentUserRank();
});

// Global refresh function
function refreshLeaderboard() {
    const refreshBtn = document.querySelector('.refresh-btn');
    const icon = refreshBtn.querySelector('i');
    
    // Add spinning animation
    icon.classList.add('fa-spin');
    refreshBtn.disabled = true;
    
    // Trigger refresh
    setTimeout(() => {
        window.location.reload();
    }, 500);
}