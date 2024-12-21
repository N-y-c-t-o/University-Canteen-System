// Select the container and clone it to create a dark-themed container
const container = document.querySelector('.container');
const cloneContainer = container.cloneNode(true);
cloneContainer.id = "dark-container";
document.body.appendChild(cloneContainer);
cloneContainer.classList.remove('active');

// Select toggle icons and elements
const toggleIcons = document.querySelectorAll('.toggle-icon');
const icons = document.querySelectorAll('.toggle-icon i');
const darkContainer = document.querySelector('#dark-container');

// Adjust the image properties inside the dark container
const darkContainerImg = darkContainer.querySelector('.home-img img');

darkContainerImg.style.width = '420px'; 
darkContainerImg.style.height = '330px'; 
darkContainerImg.style.position = 'relative'; 
darkContainerImg.style.right = '58%'; 
darkContainerImg.style.margin = '68%'; 
darkContainerImg.src = "photos/mala.png";

// Toggle between light and dark containers when the icon is clicked
toggleIcons.forEach(toggle => {
    toggle.addEventListener('click', () => {

        // Disable the toggle button for 1.5 seconds to prevent rapid switching
        toggle.classList.add('disabled');
        setTimeout(() => {
            toggle.classList.remove('disabled');
        }, 1500);

        // Toggle between sun and moon icons
        icons.forEach(icon => {
            icon.classList.toggle('bx-sun');
        });

        // Toggle the active class to switch between containers
        container.classList.toggle('active');
        darkContainer.classList.toggle('active');
    });
});
