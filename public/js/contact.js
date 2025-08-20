document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contactForm");
    const formMessage = document.getElementById("formMessage");

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        formMessage.textContent = "";
        const formData = new FormData(form);
        fetch("/contact", {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    formMessage.textContent = data.message;
                    formMessage.style.color = "green";
                    form.reset();
                } else {
                    formMessage.textContent =
                        data.message || "Something went wrong.";
                    formMessage.style.color = "red";
                }
            })
            .catch(() => {
                formMessage.textContent =
                    "An error occurred. Please try again.";
                formMessage.style.color = "red";
            });
    });
});
