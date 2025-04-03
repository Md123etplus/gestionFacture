console.log("Script chargé");
const modeToggle = document.getElementById('modeToggle');


function toggleMenu() {
    const menu = document.querySelector('.menu');
    const menuIcon = document.querySelector('.menu-icon');
    const menuI = document.querySelector('.menu-i');
    const menuIc = document.querySelector('.menu-ic');

    if (menu) {
        menu.classList.toggle('open');
    }

    if (menuI && menuIc) {
        menuI.style.display = 'inline-block';  
        menuIc.style.display = 'none'; 
    }
}

function closeMenu(event) {
    const menu = document.querySelector('.menu');

    if (menu && (!event.relatedTarget || !menu.contains(event.relatedTarget))) {
        menu.classList.remove('open');
    }
}