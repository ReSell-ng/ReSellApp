document.addEventListener('DOMContentLoaded', function() {
    const googlesignin = document.getElementById("googlesignin")
    const createaccount = document.getElementById("createaccount")
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    const toggleConfirmPassword = document.getElementById("toggle-confirmpassword")



    togglePassword.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text": "password";
        passwordInput.setAttribute("type", type);

        this.innerHTML = type === "password" ? '<i class="fa fa-eye"></i>': '<i class="fa fa-eye-slash"></i>';
    });
    
    toggleConfirmPassword.addEventListener("click", function () {
        // Toggle the type attribute
        const type = confirmPasswordInput.getAttribute("type") === "password" ? "text": "password";
        confirmPasswordInput.setAttribute("type", type);

        // Toggle the icon for visibility
        this.innerHTML = type === "password" ? '<i class="fa fa-eye"></i>': '<i class="fa fa-eye-slash"></i>';
    });
})

// Function to toggle password visibility
document.getElementById('toggle-password').addEventListener('click', function() {
    var passwordField = document.getElementById('password');
    var type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
});

// Function to toggle confirm password visibility
document.getElementById('toggle-confirmpassword').addEventListener('click', function() {
    var confirmPasswordField = document.getElementById('confirmPassword');
    var type = confirmPasswordField.type === 'password' ? 'text' : 'password';
    confirmPasswordField.type = type;
});

// Function to check if passwords match
document.getElementById('confirmPassword').addEventListener('input', function() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    var messageElement = document.getElementById('password-match-message');
    
    if (password !== confirmPassword) {
        messageElement.textContent = "Passwords do not match";
        messageElement.style.color = "red";  // Red message for mismatch
    } else {
        messageElement.textContent = "Passwords match";
        messageElement.style.color = "green";  // Green message for match
    }
});