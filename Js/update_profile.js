$(document).ready(function () {
    const userData = JSON.parse(localStorage.getItem("userData"));

    if (!userData) {
        window.location.href = 'login.html';
    }

    $("#update-form").submit(function (e) {
        e.preventDefault();

        const newUsername = $("#new-username").val();
        const newMobile = $("#new-mobile").val();
        const newLocation = $("#new-location").val();

        console.log("Updating profile");
        userData.username = newUsername;
        userData.mobile = newMobile;
        userData.location = newLocation;
        localStorage.setItem("userData", JSON.stringify(userData));
        $.ajax({
            type: "POST",
            url: "http://localhost/loginform/php/update_profile.php",
            data: {
                token: userData.token,  
                newUsername,
                newMobile,
                newLocation
            },
            success: function (response) {
                console.log(response);
                alert("Profile updated successfully");
                window.location.href = 'profile.html';
            },
            error: function (xhr, status, error) {
                console.error("Error updating profile:", error);
                alert("Error updating profile. Please try again later.");
            },
        });
        
    });
});
