// Run JavaScript only after the page has fully loaded
document.addEventListener("DOMContentLoaded", () => {

    // Select all forms using the shared form-card class
    const forms = document.querySelectorAll(".form-card");

    // Apply validation to each form
    forms.forEach(form => {

        form.addEventListener("submit", function (e) {

            let errors = [];

            // Remove any previous JavaScript error box
            const oldErrorBox = form.querySelector(".js-error-box");
            if (oldErrorBox) oldErrorBox.remove();

            // Select all form inputs
            const inputs = form.querySelectorAll("input, textarea, select");

            inputs.forEach(input => {

                // Remove previous red border styling
                input.classList.remove("error");

                // Check required visible fields are not empty
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

            // Validate email format if email field exists
            const emailField = form.querySelector('input[type="email"]');

            if (emailField && emailField.value.trim()) {
                const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;

                if (!emailPattern.test(emailField.value.trim())) {
                    errors.push("Please enter a valid email address.");
                    emailField.classList.add("error");
                }
            }

            // Check password confirmation fields match
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

            // Validate project date logic
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

            // If errors exist, stop form submission and display them
            if (errors.length > 0) {
                e.preventDefault();

                // Create error container
                const errorBox = document.createElement("div");
                errorBox.classList.add("error-box", "js-error-box");

                // Add each error message
                errors.forEach(error => {
                    const p = document.createElement("p");
                    p.textContent = error;
                    errorBox.appendChild(p);
                });

                // Insert errors at top of form
                form.prepend(errorBox);
            }

        });

    });

});
