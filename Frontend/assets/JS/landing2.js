const headings = [
    "Discover amazing Deals on Pre-Loved Items",
    "ReSell Your Items with Ease",
    "Find Great Deals, Near and Far"
];

const subheadings = [
    "Find great items from trusted sellers, right in your community.",
    "List your items effortlessly and connect with buyers.",
    "Your one-stop marketplace for pre-loved treasures."
];

let currentIndex = 0;

function toggleStickyNavbar() {
    const navbar = document.querySelector('.navbar');
    const heroSearch = document.getElementById('hero-search');
    const navbarSearch = document.getElementById('navbar-search');

    if (window.scrollY > 200) {
        navbar.classList.add('sticky');
        heroSearch.style.display = 'none';
        navbarSearch.style.display = 'flex';
        navbarSearch.style.width = '50%';
    } else {
        navbar.classList.remove('sticky');
        heroSearch.style.display = 'flex';
        navbarSearch.style.display = 'none';
    }
}

function changeText() {
    const heroHeading = document.getElementById('hero-heading'); 
    const heroSubheading = document.getElementById('hero-subheading');

    // Remove fade-in class and add fade-out class
    heroHeading.classList.remove('fade-in');
    heroSubheading.classList.remove('fade-in');
    heroHeading.classList.add('fade-out');
    heroSubheading.classList.add('fade-out');

    setTimeout(() => {
        // Update text after fade-out effect completes
        heroHeading.textContent = headings[currentIndex];
        heroSubheading.textContent = subheadings[currentIndex];

        // Cycle through headings
        currentIndex = (currentIndex + 1) % headings.length;

        // Remove fade-out and add fade-in
        heroHeading.classList.remove('fade-out');
        heroSubheading.classList.remove('fade-out');
        heroHeading.classList.add('fade-in');
        heroSubheading.classList.add('fade-in');
    }, 500); // Match this to the CSS transition duration
}

// Change text every 5 seconds
setInterval(changeText, 5000);

window.onscroll = toggleStickyNavbar;
