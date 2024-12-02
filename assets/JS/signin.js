document.addEventListener('DOMContentLoaded', function() {
    const loginbtn = document.getElementById("loginbtn")
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");
    
    
    
    togglePassword.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text": "password";
        passwordInput.setAttribute("type", type);

        this.innerHTML = type === "password" ? '<i class="fa fa-eye"></i>': '<i class="fa fa-eye-slash"></i>';
    });
})
