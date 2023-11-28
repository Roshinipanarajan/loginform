$(document).ready(function () {
    $("#signup-form").submit(function (e) {
        e.preventDefault();
        // $("#error-messages").empty();

        const username = $("#username").val();
        const password = $("#password").val();
        const email = $("#email").val();
        const mobile = $("#mobile").val();
        const location = $("#location").val();

        const userData = {
            username,
            password,
            email,
            mobile,
            location,
        };

        const usernameRegex = /^[A-Za-z\s]+$/;
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const mobileRegex = /^[0-9]+$/;
        const locationRegex = /^[A-Za-z\s]+$/;

        const errorMessages = [];

        if (!emailRegex.test(email)) {
            errorMessages.push("Please enter a valid email address.");
        }

        if (!mobileRegex.test(mobile)) {
            errorMessages.push("Mobile number must contain only digits.");
        }

        if (!usernameRegex.test(username)) {
            errorMessages.push("Username must contain only alphabetic characters and spaces.");
        }

        if (!passwordRegex.test(password)) {
            errorMessages.push("Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one digit.");
        }

        if (!locationRegex.test(location)) {
            errorMessages.push("Location must contain only alphabetic characters and spaces.");
        }

        if (errorMessages.length > 0) {
            $("#error-messages").html(errorMessages.join("<br>"));
            return;
        }

        $.ajax({
            type: "POST",
            url: "http://localhost/loginform/php/signup.php",
            data: userData,
            success: function (response) {
                console.log(response);

                    localStorage.setItem("userData", JSON.stringify(userData));
                    console.log('Redirecting to profile.html');
                    window.location.href = 'profile.html';
            },
            error: function () {
                alert("Error submitting the form. Please try again later.");
            },
        });
    });
});
