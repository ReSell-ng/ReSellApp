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