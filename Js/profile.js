$(document).ready(function () {
    const userData = JSON.parse(localStorage.getItem("userData"));

    if (userData) {
        const profileHtml = `
            <p><strong>Username:</strong> ${userData.username}</p>
            <p><strong>Email:</strong> ${userData.email}</p>
            <p><strong>Mobile:</strong> ${userData.mobile}</p>
            <p><strong>Location:</strong> ${userData.location}</p>
        `;
        $("#user-data").html(profileHtml);
    } else {
        window.location.href = 'login.html';
    }
    $("#logout-button").click(function () {
        localStorage.removeItem("userData");
        window.location.href = 'login.html';
    });
    $("#update-button").click(function () {
        window.location.href = 'update_profile.html';
    });
});
