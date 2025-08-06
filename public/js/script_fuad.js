document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registrationForm');
  const message = document.getElementById('eventMessage');
  const successSection = document.getElementById('successSection');
  const backBtn = document.querySelector('.back-btn');

  // Get selected event from URL parameter
  const urlParams = new URLSearchParams(window.location.search);
  const selectedEvent = urlParams.get('event') || 'general';

  // Get event details based on selection
  const eventDetails = {
    'park-cleanup': {
      name: 'Sher-e-Bangla Park Cleanup',
      location: 'Agargaon',
      date: 'Aug 15, 8:00 AM',
      icon: 'fas fa-leaf'
    },
    'beach-cleanup': {
      name: 'Cox\'s Bazar Beach Cleanup',
      location: 'Laboni Point',
      date: 'Aug 20, 6:00 AM',
      icon: 'fas fa-water'
    },
    'city-street': {
      name: 'Mirpur-1 Street Cleaning',
      location: 'Section-2',
      date: 'Aug 25, 7:00 AM',
      icon: 'fas fa-city'
    },
    'riverbank-cleanup': {
      name: 'Turag River Bank Cleanup',
      location: 'Uttara Sector-18',
      date: 'Aug 30, 7:30 AM',
      icon: 'fas fa-tree'
    },
    'general': {
      name: 'General Volunteer Registration',
      location: 'All Events',
      date: 'Multiple Dates',
      icon: 'fas fa-hands-helping'
    }
  };

  // Check if user is already registered for any events
  const registeredEvents = JSON.parse(localStorage.getItem('registeredEvents') || '[]');
  
  if (registeredEvents.length > 0) {
    // Hide form and show success section
    form.style.display = 'none';
    successSection.style.display = 'block';
    backBtn.style.display = 'none';
    updateEventsList();
  }

  // Update form title based on selected event
  const formTitle = document.querySelector('h1');
  if (selectedEvent !== 'general') {
    formTitle.textContent = `Register for ${eventDetails[selectedEvent].name}`;
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Get form data
      const formData = new FormData(form);
      const volunteerData = {
        name: formData.get('name'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        address: formData.get('address'),
        tools: formData.get('tools'),
        registrationDate: new Date().toISOString()
      };

      // Save volunteer data to localStorage
      localStorage.setItem('volunteerData', JSON.stringify(volunteerData));
      
      // Add selected event to registered events
      let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents') || '[]');
      if (!registeredEvents.includes(selectedEvent)) {
        registeredEvents.push(selectedEvent);
        localStorage.setItem('registeredEvents', JSON.stringify(registeredEvents));
      }

      // Hide form and show success section
      form.style.display = 'none';
      successSection.style.display = 'block';
      backBtn.style.display = 'none';

      // Update events list
      updateEventsList();

      // Show success message briefly
      message.innerHTML = '<div style="color: #4CAF50; text-align: center; margin: 10px 0;"><i class="fas fa-check-circle"></i> Processing registration...</div>';
      message.style.display = 'block';
      
      setTimeout(() => {
        message.style.display = 'none';
      }, 2000);

      // Add animation to success section
      successSection.style.opacity = '0';
      successSection.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        successSection.style.transition = 'all 0.5s ease';
        successSection.style.opacity = '1';
        successSection.style.transform = 'translateY(0)';
      }, 100);
    });
  }

  // Function to update events list
  function updateEventsList() {
    const registeredEvents = JSON.parse(localStorage.getItem('registeredEvents') || '[]');
    const eventList = document.querySelector('.event-list');
    
    if (eventList) {
      eventList.innerHTML = '';
      
      // Show all events with their registration status
      Object.keys(eventDetails).forEach(eventKey => {
        if (eventKey === 'general') return; // Skip general registration
        
        const event = eventDetails[eventKey];
        const isRegistered = registeredEvents.includes(eventKey);
        
        const eventCard = document.createElement('div');
        eventCard.className = 'mini-event-card';
        eventCard.innerHTML = `
          <i class="${event.icon}"></i>
          <div class="event-info">
            <h4>${event.name}</h4>
            <p><i class="fas fa-map-marker-alt"></i> ${event.location} | <i class="fas fa-clock"></i> ${event.date}</p>
          </div>
          ${isRegistered 
            ? '<span class="status registered">✓ Registered</span>'
            : `<button class="join-btn" onclick="joinEvent('${eventKey}')">Join Event</button>`
          }
        `;
        eventList.appendChild(eventCard);
      });
    }
  }

  // Function to join additional events
  window.joinEvent = function(eventKey) {
    let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents') || '[]');
    if (!registeredEvents.includes(eventKey)) {
      registeredEvents.push(eventKey);
      localStorage.setItem('registeredEvents', JSON.stringify(registeredEvents));
      updateEventsList();
      
      // Show success message
      const tempMessage = document.createElement('div');
      tempMessage.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #4CAF50; color: white; padding: 1rem; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.2); animation: slideIn 0.3s ease-out;';
      tempMessage.innerHTML = `<i class="fas fa-check-circle"></i> Successfully joined ${eventDetails[eventKey].name}!`;
      document.body.appendChild(tempMessage);
      
      setTimeout(() => {
        document.body.removeChild(tempMessage);
      }, 3000);
    }
  };

  // Add reset registration function (for testing)
  window.resetRegistration = function() {
    localStorage.removeItem('registeredEvents');
    localStorage.removeItem('volunteerData');
    location.reload();
  };
});
