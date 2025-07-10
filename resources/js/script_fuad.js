document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('volunteerForm');
  const message = document.getElementById('volunteerMessage');
  const backBtn = document.getElementById('backHomeBtn');

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Simulate form processing
      message.innerText = "Thanks for registering! We'll contact you soon.";
      message.style.display = "block";

      // Show Back to Home button
      backBtn.style.display = "block";

      // Optional: clear form
      form.reset();
    });
  }
});
