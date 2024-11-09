document.addEventListener('DOMContentLoaded', function() {
    const googlesignin = document.getElementById("googlesignin")
    const loginbtn = document.getElementById("loginbtn")
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");
    
    
    
    togglePassword.addEventListener("click", function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute("type") === "password" ? "text": "password";
        passwordInput.setAttribute("type", type);

        // Toggle the icon for visibility
        this.innerHTML = type === "password" ? '<i class="fa fa-eye"></i>': '<i class="fa fa-eye-slash"></i>';
    });
})
