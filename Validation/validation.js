function validateEmail() {
    var email = document.getElementById("email").value;
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (!email.match(emailPattern)) {
        alert("Invalid email address. Please enter a valid email.");
        return false;
    }

    return true;
}


function validateForm() {

    var isEmailValid = validateEmail();

    return isEmailValid;

}