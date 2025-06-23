document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("edit-data").addEventListener("click", function(event) {
        event.preventDefault();
        loadContent("crud.php");
    });

    document.getElementById("register").addEventListener("click", function(event) {
        event.preventDefault();
        loadContent("register.php");
    });
});

function loadContent(url) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => console.error("Error loading content:", error));
}

function validatePassword() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
    }
    return true;
}