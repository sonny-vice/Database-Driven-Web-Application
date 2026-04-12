document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll(".form-card");

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            let errors = [];

            const oldErrorBox = form.querySelector(".js-error-box");
            if (oldErrorBox) oldErrorBox.remove();

            const inputs = form.querySelectorAll("input, textarea, select");

            inputs.forEach(input => {
                input.classList.remove("error");

                if (
                    input.type !== "hidden" &&
                    input.hasAttribute("required") &&
                    !input.value.trim()
                ) {
                    const label = input.name.replaceAll("_", " ");
                    errors.push(`${label} is required.`);
                    input.classList.add("error");
                }
            });

            const emailField = form.querySelector('input[type="email"]');
            if (emailField && emailField.value.trim()) {
                const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
                if (!emailPattern.test(emailField.value.trim())) {
                    errors.push("Please enter a valid email address.");
                    emailField.classList.add("error");
                }
            }

            const passwordField = form.querySelector('input[name="password"]');
            const confirmField = form.querySelector('input[name="confirm_password"]');

            if (
                passwordField &&
                confirmField &&
                passwordField.value &&
                confirmField.value &&
                passwordField.value !== confirmField.value
            ) {
                errors.push("Passwords do not match.");
                passwordField.classList.add("error");
                confirmField.classList.add("error");
            }

            const startDateField = form.querySelector('input[name="start_date"]');
            const endDateField = form.querySelector('input[name="end_date"]');

            if (
                startDateField &&
                endDateField &&
                startDateField.value &&
                endDateField.value &&
                endDateField.value < startDateField.value
            ) {
                errors.push("End date cannot be earlier than start date.");
                startDateField.classList.add("error");
                endDateField.classList.add("error");
            }

            if (errors.length > 0) {
                e.preventDefault();

                const errorBox = document.createElement("div");
                errorBox.classList.add("error-box", "js-error-box");

                errors.forEach(error => {
                    const p = document.createElement("p");
                    p.textContent = error;
                    errorBox.appendChild(p);
                });

                form.prepend(errorBox);
            }
        });
    });
});