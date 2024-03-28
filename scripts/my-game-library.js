let gameWrappers = document.querySelectorAll(".sale-wrapper .game-wrapper");
let overlay = document.querySelector(".overlay");
gameWrappers.forEach(gameWrapper => {
    gameWrapper.addEventListener("mouseenter",()=>{
        imageURL = gameWrapper.querySelector(".game-image-wrapper img").getAttribute("src");
        let styleAttribute = `background-image: url(${imageURL});`;
        overlay.setAttribute("style",styleAttribute);
        overlay.style.opacity = 0.1;
    });
    gameWrapper.addEventListener("mouseleave",()=>{
        overlay.style.opacity = 0;
    });
});