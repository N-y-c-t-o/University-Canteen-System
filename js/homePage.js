 const body = document.querySelector("body"),
     modeToggle = document.querySelector(".mode-toggle");

 modeToggle.addEventListener("click", () => {
     body.classList.toggle("dark");

});