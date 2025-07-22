document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        formMessage.textContent = 'Thank you for contacting us! We will get back to you soon.';
        form.reset();
    });
});