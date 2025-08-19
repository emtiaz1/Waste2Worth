document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wasteForm');
    const recentReports = document.getElementById('recentReports');
    const totalWaste = document.getElementById('totalWaste');
    const mostType = document.getElementById('mostType');
    const cleanupBar = document.getElementById('cleanupBar');
    const cleanupText = document.getElementById('cleanupText');

    // Submit form and save to database
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/wastereport', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.reset();
                loadReports();
                loadStats();
            } else {
                alert('Failed to submit report.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to submit report.');
        });
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
                let percent = stats.total > 0 ? Math.round((stats.cleaned / stats.total) * 100) : 0;
                cleanupBar.style.width = percent + '%';
                cleanupText.textContent = percent + '% of reported waste cleaned';
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                totalWaste.textContent = '0 kg';
                mostType.textContent = 'None';
                cleanupBar.style.width = '0%';
                cleanupText.textContent = '0% of reported waste cleaned';
            });
    }

    // Load data on page load
    loadReports();
    loadStats();
});
