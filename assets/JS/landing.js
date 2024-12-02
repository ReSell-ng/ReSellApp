document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById("hamburger")
    const sidemenu = document.getElementById("sidemenu")
    const profilebtn = document.getElementById("profilebtn")
    const profileclosebtn = document.getElementById("profileclosebtn")
    const cartbtn = document.getElementById("cartbtn")
    const searchbtn = document.getElementById("searchbtn")
    const sellwidget = document.getElementById("sellwidget")
    const accessorieswidget = document.getElementById("accessorieswidget")
    const laptopwidget = document.getElementById("laptopwidget")
    const foodwidget = document.getElementById("foodwidget")
    const backbtn = document.getElementById("backbtn")
    const signinbtn = document.getElementById("signinbtn")
    const signupbtn = document.getElementById("signupbtn")

    hamburger.addEventListener('click', function opensidemenu() {
        sidemenu.classList.add("active");
    });
    backbtn.addEventListener('click', function closesidemenu() {
        sidemenu.classList.remove("active");
    });
    profilebtn.addEventListener('click', function openprofilemenu() {
        profilemenu.classList.add("active");
    });
    profileclosebtn.addEventListener('click', function closeprofilemunu() {
        profilemenu.classList.remove("active")
    });

    signupbtn.addEventListener('click', function signup() {
        window.location.href = "signup.html"
    })
    cartbtn.addEventListener('click', function() {
        alert("please sign in")
    })
})