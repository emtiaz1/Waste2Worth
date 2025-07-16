
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("footer form");
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = form.querySelector("input[type='email']").value.trim();
      if (!email || !email.includes("@")) {
        alert("Please enter a valid email address.");
        return;
      }
      alert("Thank you for subscribing!");
      form.reset();
    });
  }
});
