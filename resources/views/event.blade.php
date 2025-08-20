<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cleanup Events</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/event.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}" />
</head>

<body>
  @include('layouts.appbar')
  <div class="layout" id="mainLayout">
    <div class="main-content">
      <div class="container" style="max-width: 1200px; background: transparent; box-shadow: none; padding: 20px;">
  <h1>Upcoming Cleanup Events</h1>
        <div id="eventSignupFormSection" style="margin-bottom:32px;display:none;">
          <div style="background:#f8f9fa;border-radius:16px;box-shadow:0 2px 16px rgba(44,62,80,0.08);padding:32px 24px;max-width:520px;margin:auto;">
            <h2 style="text-align:center;color:#2196F3;margin-bottom:24px;font-size:2rem;font-weight:700;letter-spacing:1px;">Sign Up for an Event</h2>
            <form id="eventSignupForm">
              <div style="margin-bottom:18px;">
                <label for="eventSelect" style="font-weight:600;color:#333;">Select Event:</label>
                <select id="eventSelect" name="event" required style="width:100%;padding:10px;border-radius:8px;border:1px solid #bdbdbd;margin-top:6px;">
                  <option value="">-- Choose an Event --</option>
                  @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                  @endforeach
                </select>
              </div>
              <div style="display:flex;gap:12px;margin-bottom:18px;">
                <input type="text" name="name" placeholder="Full Name" required style="flex:1;padding:10px;border-radius:8px;border:1px solid #bdbdbd;" />
                <input type="email" name="email" placeholder="Email Address" required style="flex:1;padding:10px;border-radius:8px;border:1px solid #bdbdbd;" />
              </div>
              <div style="display:flex;gap:12px;margin-bottom:18px;">
                <input type="tel" name="phone" placeholder="Phone Number" required style="flex:1;padding:10px;border-radius:8px;border:1px solid #bdbdbd;" />
                <input type="text" name="address" placeholder="Present Address" required style="flex:1;padding:10px;border-radius:8px;border:1px solid #bdbdbd;" />
              </div>
              <div style="margin-bottom:18px;">
                <textarea name="tools" placeholder="Any special skills or tools you'll bring?" rows="3" style="width:100%;padding:10px;border-radius:8px;border:1px solid #bdbdbd;"></textarea>
              </div>
              <button type="submit" style="width:100%;margin-top:8px;padding:12px 0;font-size:1.1em;border-radius:8px;background:#4CAF50;color:#fff;border:none;cursor:pointer;font-weight:600;letter-spacing:1px;transition:background 0.2s;">Join</button>
            </form>
            <div id="eventSignupMsg" style="margin-top:16px;color:#388e3c;font-weight:bold;text-align:center;"></div>
          </div>
        </div>
        <button id="showEventSignupBtn" style="margin-bottom:24px;padding:10px 24px;font-size:1.1em;border-radius:8px;background:#2196F3;color:#fff;border:none;cursor:pointer;">Sign Up for an Event</button>
        <div class="event-grid">
          @foreach($events as $event)
          <div class="event-card">
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}">
            <h2>{{ $event->name }}</h2>
            <div class="event-details">
              <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}</p>
            </div>
            <button class="more-info-btn" data-event-id="{{ $event->id }}">More Info</button>
          </div>
          @endforeach
        </div>
        <div style="margin-top:40px;">
          <button id="showStatusBtn" class="status-btn">Show Other Events & Sign Up Status</button>
          <div id="eventStatusWrapper" style="display:none;">
            <h2 style="text-align:center;color:#4CAF50;margin-bottom:24px;font-size:1.7rem;font-weight:700;letter-spacing:1px;">Other Events & Sign Up Status</h2>
            <div id="eventStatusSection" class="event-status-grid"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/appbar.js') }}"></script>
  <!-- Remove static eventStatus.js, use dynamic rendering below -->
  <style>
    .event-modal-bg {position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(44,62,80,0.45);z-index:999;display:flex;align-items:center;justify-content:center;}
    .event-modal {background:#fff;border-radius:16px;box-shadow:0 2px 24px rgba(44,62,80,0.18);padding:32px 28px;max-width:420px;width:90vw;position:relative;}
    .event-modal h3 {margin-top:0;color:#2196F3;font-size:1.5rem;}
    .event-modal p {margin-bottom:10px;}
    .event-modal .close-btn {position:absolute;top:12px;right:16px;background:none;border:none;font-size:1.5rem;color:#888;cursor:pointer;}

    .status-btn {
      padding:12px 32px;font-size:1.15em;border-radius:10px;background:#4CAF50;color:#fff;border:none;cursor:pointer;font-weight:600;box-shadow:0 2px 8px rgba(44,62,80,0.08);transition:background 0.2s;
    }
    .status-btn:hover {background:#388e3c;}
    .event-status-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 32px;
      margin-top: 18px;
    }
    .event-status-card {
      background: linear-gradient(135deg, #e3f2fd 0%, #fce4ec 100%);
      border-radius: 18px;
      box-shadow: 0 4px 18px rgba(44,62,80,0.13);
      padding: 28px 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: box-shadow 0.2s, transform 0.2s;
      position: relative;
      min-height: 120px;
    }
    .event-status-card:hover {
      box-shadow: 0 8px 32px rgba(44,62,80,0.22);
      transform: translateY(-3px) scale(1.03);
    }
    .event-status-card .event-info {
      flex: 1;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .event-status-card h4 {
      margin: 0 0 8px 0;
      font-size: 1.25rem;
      color: #1976D2;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .event-status-card h4 .fa-calendar-alt {
      color: #388e3c;
      font-size: 1.1em;
    }
    .event-status-card p {
      margin: 0 0 8px 0;
      font-size: 1.05em;
      color: #555;
      font-weight: 500;
      letter-spacing: 0.2px;
    }
    .event-status-card .status {
      display: inline-block;
      padding: 8px 22px;
      border-radius: 12px;
      font-weight: 700;
      margin-top: 10px;
      font-size: 1.05em;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      transition: background 0.2s, color 0.2s;
    }
    .event-status-card .status.signedup {
      background: #c8e6c9;
      color: #2e7d32;
      border: 1px solid #81c784;
    }
    .event-status-card .status.notsignedup {
      background: #ffcdd2;
      color: #c62828;
      border: 1px solid #e57373;
    }
    .event-status-card .join-btn {
      padding:7px 18px;border-radius:8px;background:#2196F3;color:#fff;border:none;cursor:pointer;font-weight:600;margin-top:8px;font-size:0.98em;transition:background 0.2s;
    }
    .event-status-card .join-btn:hover {background:#1565c0;}
  </style>
  <script>
    document.getElementById('showStatusBtn').addEventListener('click', function() {
      document.getElementById('eventStatusWrapper').style.display = 'block';
      this.style.display = 'none';
    });
    document.getElementById('showEventSignupBtn').addEventListener('click', function() {
      document.getElementById('eventSignupFormSection').style.display = 'block';
      this.style.display = 'none';
    });

    // Pass PHP event data to JS
    const eventsData = @json($events);
    document.querySelectorAll('.more-info-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const eventId = this.getAttribute('data-event-id');
        const info = eventsData.find(e => e.id == eventId);
        const modalBg = document.createElement('div');
        modalBg.className = 'event-modal-bg';
        modalBg.innerHTML = `
          <div class='event-modal'>
            <button class='close-btn'>&times;</button>
            <h3>${info.name}</h3>
            <p><strong>Location:</strong> ${info.location}</p>
            <p><strong>Time:</strong> ${info.time}</p>
            <p><strong>Date:</strong> ${new Date(info.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
            <p>${info.description}</p>
          </div>
        `;
        document.body.appendChild(modalBg);
        modalBg.querySelector('.close-btn').onclick = function() {
          document.body.removeChild(modalBg);
        };
      });
    });

    // Dynamic Other Events & Sign Up Status rendering
    function renderEventStatus() {
      const statusSection = document.getElementById('eventStatusSection');
      if (!statusSection) return;
      statusSection.innerHTML = '';
      let signedUpEvents = JSON.parse(localStorage.getItem('signedUpEvents') || '[]');
      eventsData.forEach(event => {
        const isSignedUp = signedUpEvents.includes(String(event.id));
        const card = document.createElement('div');
        card.className = 'event-status-card';
        card.innerHTML = `
          <div class='event-info'>
            <h4><i class="fas fa-calendar-alt"></i> ${event.name}</h4>
            <p>${new Date(event.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
            <span class="status ${isSignedUp ? 'signedup' : 'notsignedup'}">${isSignedUp ? '<i class="fas fa-check-circle"></i> Signed Up' : '<i class="fas fa-times-circle"></i> Not Signed Up'}</span>
          </div>
        `;
        statusSection.appendChild(card);
      });
    }
    document.getElementById('showStatusBtn').addEventListener('click', function() {
      document.getElementById('eventStatusWrapper').style.display = 'block';
      this.style.display = 'none';
      renderEventStatus();
    });
    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('join-btn')) {
        const key = e.target.getAttribute('data-key');
        let signedUpEvents = JSON.parse(localStorage.getItem('signedUpEvents') || '[]');
        if (!signedUpEvents.includes(key)) {
          signedUpEvents.push(key);
          localStorage.setItem('signedUpEvents', JSON.stringify(signedUpEvents));
          renderEventStatus();
        }
      }
    });

    // Dynamic event select for sign up form
    document.getElementById('eventSignupForm').addEventListener('submit', function(e) {
      e.preventDefault();
      var form = e.target;
      var eventId = form.event.value;
      var name = form.name.value;
      var email = form.email.value;
      var phone = form.phone.value;
      var address = form.address.value;
      var tools = form.tools.value;
      // Save signup data to localStorage
      var signupData = { eventId, name, email, phone, address, tools };
      var allSignups = JSON.parse(localStorage.getItem('eventSignups') || '[]');
      allSignups.push(signupData);
      localStorage.setItem('eventSignups', JSON.stringify(allSignups));
      // Mark event as signed up
      var signedUpEvents = JSON.parse(localStorage.getItem('signedUpEvents') || '[]');
      if (!signedUpEvents.includes(eventId)) {
        signedUpEvents.push(eventId);
        localStorage.setItem('signedUpEvents', JSON.stringify(signedUpEvents));
      }
      document.getElementById('eventSignupMsg').textContent = 'Successfully signed up for ' + eventsData.find(e => String(e.id) === eventId).name + '!';
      form.reset();
    });
  </script>
</body>

</html>