$(document).ready(function () {
    $("#login-form").submit(function (e) {
        e.preventDefault();

        const email = $("#email").val();
        const password = $("#password").val();

        const userData = {
            email,
            password,
        };

        $.ajax({
            type: "POST",
            url: "http://localhost/loginform/php/login.php",
            data: userData,
            success: function (response) {
                console.log(response.success);

                if (response.success) {
                    localStorage.setItem("userData", JSON.stringify(response.userData));
                    localStorage.setItem("token", response.token); // Save the token
                    console.log('Redirecting to profile.html');
                    window.location.href = 'profile.html';
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Error submitting the form. Please try again later.");
            },
        });
    });
});
