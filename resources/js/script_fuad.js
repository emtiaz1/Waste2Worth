// Handle volunteer form
document.addEventListener('DOMContentLoaded', () => {
  const volunteerForm = document.getElementById('volunteerForm');
  const eventForm = document.getElementById('eventForm');

  if (volunteerForm) {
    volunteerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      document.getElementById('volunteerMessage').innerText = "Thanks for registering! We'll contact you soon.";
      volunteerForm.reset();
    });
  }

  if (eventForm) {
    eventForm.addEventListener('submit', function (e) {
      e.preventDefault();
      document.getElementById('eventMessage').innerText = "You have successfully signed up for the event!";
      eventForm.reset();
    });
  }
});
