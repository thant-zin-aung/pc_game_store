let addGenreButton = document.querySelector(".game-detail-wrapper .add-genre-button");
let addGenreWrapper = document.querySelector(".add-genre-wrapper");
let closeGenreButton = document.querySelector(".add-genre-wrapper .close-button");

addGenreButton.addEventListener("click",()=>{
    addGenreWrapper.style.display = "flex";
});
closeGenreButton.addEventListener("click",()=>{
    addGenreWrapper.style.display = "none";
});

let addNewGameButton = document.querySelector(".game-detail-wrapper .add-new-game-button");
addNewGameButton.addEventListener("click",event=>{
    window.location = "add-new-game.php";
});