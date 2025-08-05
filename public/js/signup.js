const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");

inputs.forEach((inp) => {
    inp.addEventListener("focus", () => {
        inp.classList.add("active");
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove("active");
    });
});

toggle_btn.forEach((btn) => {
    btn.addEventListener("click", () => {
        main.classList.toggle("sign-up-mode");
    });
});

function moveSlider() {
    let index = this.dataset.value;

    let currentImage = document.querySelector(`.img-${index}`);
    images.forEach((img) => img.classList.remove("show"));
    currentImage.classList.add("show");

    const textSlider = document.querySelector(".text-group");
    textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

    bullets.forEach((bull) => bull.classList.remove("active"));
    this.classList.add("active");
}

bullets.forEach((bullet) => {
    bullet.addEventListener("click", moveSlider);
});

function autoSlide() {
    let currentIndex = parseInt(
        document.querySelector(".bullets .active").dataset.value
    );
    let nextIndex = currentIndex === 3 ? 1 : currentIndex + 1;

    let nextBullet = document.querySelector(
        `.bullets span[data-value="${nextIndex}"]`
    );
    moveSlider.call(nextBullet);
}

document.addEventListener("DOMContentLoaded", function () {
    // Get mode from URL
    const params = new URLSearchParams(window.location.search);
    const mode = params.get("mode");
    const main = document.querySelector("main");

    if (mode === "signup") {
        main.classList.add("sign-up-mode");
    } else {
        main.classList.remove("sign-up-mode");
    }

    // Start auto-sliding every 3 seconds
    setInterval(autoSlide, 3000);
});

// Password visibility toggle
document.querySelectorAll(".password-toggle").forEach((toggle) => {
    toggle.addEventListener("click", function () {
        const passwordInput =
            this.previousElementSibling.previousElementSibling;
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        }
    });
});

// Password confirmation validation
const signupForm = document.querySelector(".sign-up-form");
const password = document.getElementById("signup-password");
const confirmPassword = document.getElementById("confirm-password");

signupForm.addEventListener("submit", function (e) {
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert("Passwords do not match!");
        confirmPassword.focus();
    }
});
