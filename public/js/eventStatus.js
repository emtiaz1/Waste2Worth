document.addEventListener('DOMContentLoaded', function () {
  // List of event keys
  const eventKeys = [
    'park-cleanup',
    'beach-cleanup',
    'city-street',
    'riverbank-cleanup'
  ];

  // Event details
  const eventDetails = {
    'park-cleanup': {
      name: 'Sher-e-Bangla Park Cleanup',
      location: 'Sher-e-Bangla Nagar Park, Agargaon',
      date: 'August 15, 2025',
      image: 'frontend/image/clean1.jpg'
    },
    'beach-cleanup': {
      name: "Cox's Bazar Beach Cleanup",
      location: "Cox's Bazar Sea Beach, Laboni Point",
      date: 'August 20, 2025',
      image: 'frontend/image/clean2.png'
    },
    'city-street': {
      name: 'Mirpur-1 Street Cleaning',
      location: 'Mirpur-1, Section-2, Main Road',
      date: 'August 25, 2025',
      image: 'frontend/image/clean3.png'
    },
    'riverbank-cleanup': {
      name: 'Turag River Bank Cleanup',
      location: 'Turag River, Uttara Sector-18',
      date: 'August 30, 2025',
      image: 'frontend/image/clean4.jpg'
    }
  };

  // Get signed up events from localStorage
  let signedUpEvents = JSON.parse(localStorage.getItem('signedUpEvents') || '[]');

  // Function to render event signup status
  function renderEventStatus() {
    const statusSection = document.getElementById('eventStatusSection');
    if (!statusSection) return;
    statusSection.innerHTML = '';
    eventKeys.forEach(key => {
      const event = eventDetails[key];
      const isSignedUp = signedUpEvents.includes(key);
      const card = document.createElement('div');
      card.className = 'event-status-card';
      card.innerHTML = `
        <img src='/${event.image}' alt='${event.name}' style='width:80px;height:60px;border-radius:8px;'>
        <div style='display:inline-block;vertical-align:top;margin-left:12px;'>
          <h4 style='margin:0;'>${event.name}</h4>
          <p style='margin:0;font-size:0.95em;'>${event.location} | ${event.date}</p>
          ${isSignedUp
            ? '<span style="color:green;font-weight:bold;">Already Signed Up</span>'
            : `<button class='signup-btn' data-key='${key}'>Sign Up</button>`}
        </div>
      `;
      statusSection.appendChild(card);
    });
  }

  // Handle sign up button click
  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('signup-btn')) {
      const key = e.target.getAttribute('data-key');
      if (!signedUpEvents.includes(key)) {
        signedUpEvents.push(key);
        localStorage.setItem('signedUpEvents', JSON.stringify(signedUpEvents));
        renderEventStatus();
      }
    }
  });

  // Initial render
  renderEventStatus();
});
