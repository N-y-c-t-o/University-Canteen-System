//  const body = document.querySelector("body"),
//      modeToggle = body.querySelector(".mode-toggle");
//      sidebar = body.querySelector("nav");
//      sidebarToggle = body.querySelector(".sidebar-toggle");

// modeToggle.addEventListener("click", () => {
//      body.classList.toggle("dark");

// });

// sidebarToggle.addEventListener("click", () => {
//     sidebar.classList.toggle("close");

// })

// scripts.js

// Select elements
const body = document.querySelector("body"),
      modeToggle = body.querySelector(".mode-toggle"),
      sidebar = body.querySelector("nav"),
      sidebarToggle = body.querySelector(".sidebar-toggle");

// Function to toggle dark mode and store preference
function toggleDarkMode() {
    body.classList.toggle("dark");
    const isDarkMode = body.classList.contains("dark");
    localStorage.setItem("theme", isDarkMode ? "dark" : "light");
}

// Function to apply the saved theme preference
function applySavedTheme() {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        body.classList.add("dark");
    } else {
        body.classList.remove("dark");
    }
}

// Apply the saved theme on page load
applySavedTheme();

// Add event listener to mode toggle button
modeToggle.addEventListener("click", toggleDarkMode);

// Sidebar toggle functionality
sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

document.addEventListener("DOMContentLoaded", function() {
    const dropdownToggle = document.querySelector('.dd-toggle');
    const collapseMenu = document.querySelector('.collapse');

    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        collapseMenu.classList.toggle('show');
    });
});

