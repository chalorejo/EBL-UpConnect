

const homeLogo = document.getElementById("homeLogo");

homeLogo.addEventListener("click", function () {
    window.location.href = "index.html"; 
});

const up = document.getElementById("up");
up.addEventListener("click", function () {
    window.location.href = "index.html"; 
});

const down = document.getElementById("down");
down.addEventListener("click", function () {
    window.location.href = "index.html";
});

function toggleMenu() {
    var mobileNav = document.getElementById("mobileNav");
    if (mobileNav.style.display === "flex") {
        mobileNav.style.display = "none";
    } else {
        mobileNav.style.display = "flex";
    }
}




