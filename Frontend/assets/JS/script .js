// Select all tab buttons and panes
const tabButtons = document.querySelectorAll('.tab-button');
const tabPanes = document.querySelectorAll('.tab-pane');

// Function to switch tabs
function switchTab(event) {
    const targetTab = event.target.getAttribute('data-tab');

    // Remove active class from all buttons and panes
    tabButtons.forEach(button => button.classList.remove('active'));
    tabPanes.forEach(pane => pane.classList.remove('active'));

    // Add active class to the clicked button and corresponding pane
    event.target.classList.add('active');
    document.getElementById(targetTab).classList.add('active');
}

// Attach click event listener to each button
tabButtons.forEach(button => {
    button.addEventListener('click', switchTab);
});
