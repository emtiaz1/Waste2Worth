document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wasteForm');
    const recentReports = document.getElementById('recentReports');

    // Handle form submission with AJAX for popup and redirect
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert(' Error: CSRF token not found. Please refresh the page.');
            return;
        }
        
        // Show loading state
        submitButton.textContent = 'Submitting...';
        submitButton.disabled = true;
        
        // Debug: log form data
        console.log('Submitting form with data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Show success popup
                alert('✅ ' + data.message + '\n\nClick OK to go to the home page.');
                
                // Redirect to home page
                window.location.href = '/';
            } else {
                // Show error message
                alert('❌ Error: ' + (data.message || 'Failed to submit report'));
                
                // Reset button
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            
            // More detailed error reporting
            let errorMessage = 'Failed to submit report. ';
            if (error.message) {
                errorMessage += 'Details: ' + error.message;
            }
            errorMessage += '\n\nTrying alternative submission method...';
            
            alert(' AJAX Error: ' + errorMessage);
            
            // Show fallback button
            const fallbackBtn = document.getElementById('fallbackSubmit');
            if (fallbackBtn) {
                fallbackBtn.style.display = 'inline-block';
            }
            
            // Reset button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    });

    // Fallback submission using traditional form
    const fallbackBtn = document.getElementById('fallbackSubmit');
    if (fallbackBtn) {
        fallbackBtn.addEventListener('click', function() {
            if (confirm('This will submit the form using traditional method. Continue?')) {
                // Remove the AJAX event listener temporarily
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                
                // Submit traditionally
                newForm.submit();
            }
        });
    }

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

    // Load recent reports on page load
    loadReports();
});
