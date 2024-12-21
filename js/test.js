window.onload = function() {
    let leaves = document.querySelectorAll('.leaf');
    leaves.forEach((leaf, index) => {
        setTimeout(() => {
            let x = Math.random() * window.innerWidth;
            let y = Math.random() * window.innerHeight;
            leaf.style.transform = `translate(${x}px, ${y}px) rotate(${Math.random() * 360}deg)`;
            leaf.style.opacity = '1';
            leaf.style.animation = 'scatter 3s ease-in-out forwards';
        }, index * 300); // Adjust delay between leaves
    });
};
