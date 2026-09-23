document.addEventListener("DOMContentLoaded", function () {

    const forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const password = form.querySelector(
                'input[name="password"]'
            );

            const confirmPassword = form.querySelector(
                'input[name="confirm_password"]'
            );

            if (password && confirmPassword) {

                if (password.value.length < 6) {

                    alert(
                        "Password must be at least 6 characters."
                    );

                    event.preventDefault();
                    return;
                }

                if (
                    password.value !==
                    confirmPassword.value
                ) {

                    alert("Passwords do not match.");

                    event.preventDefault();
                    return;
                }
            }

            const title = form.querySelector(
                'input[name="title"]'
            );

            if (title && title.value.trim() === "") {

                alert("Task title is required.");

                event.preventDefault();
            }

        });

    });

});
