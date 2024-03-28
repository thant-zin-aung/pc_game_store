let latestGameWrapperImages = document.querySelectorAll(".game-info-wrapper img");
let latestGameImageArray = [];

function inititate_latest_game_animation() {
    for ( let count = 0 ; count < latestGameWrapperImages.length ; count++ ) {
        latestGameImageArray.unshift(latestGameWrapperImages[count].src);
        latestGameWrapperImages[count].classList.add("remove-latest-game-images");
    }
}
function unhide_latest_game_images() {
    for ( let count = 0 ; count < latestGameImageArray.length ; count++ ) {
        // let randomImage = Math.floor(Math.random()*latestGameImageArray.length);
        latestGameWrapperImages[count].src = latestGameImageArray[count];
        latestGameWrapperImages[count].classList.remove("remove-latest-game-images");
    }
}

function start_latest_game_animation() {
    setInterval(() => {
        setTimeout(() => {
            unhide_latest_game_images();
        }, 400);
        inititate_latest_game_animation(); 
    }, 8000);
}
// start_latest_game_animation();


// Genre list navigation scripts....
// let genres = document.querySelectorAll("nav .middle-wrapper .genre-tab .genre-wrapper .genre");
// genres.forEach(genre=>{
//     genre.addEventListener("click",()=>{
//         window.location = "search-result.php?genre-id="+genre.getAttribute("genre-id");
//     })
// })
// localStorage.removeItem("my-cart");

// app script starts from here........
let loginSignUpWrapper = document.querySelector("nav .right-wrapper .login-signup-wrapper");
let accountInfoWrapper = document.querySelector("nav .right-wrapper .login-signup-wrapper .account-info-wrapper");
let usernameLabel = document.querySelector("nav .right-wrapper .login-signup-wrapper .label");
function checkUserLoggedIn() {
    return usernameLabel.innerHTML.trim()==="Log in / Sign up"?false:true;
}
if ( !checkUserLoggedIn() ) {
    accountInfoWrapper.style.display = "none";
};
loginSignUpWrapper.addEventListener("click",()=>{
    if ( loginSignUpWrapper.getAttribute("isUserLoggedIn") === "yes" ) {
        accountInfoWrapper.style.display="flex";    
    } else {
        window.location = "login.php";
    }
    
});
accountInfoWrapper.addEventListener("mouseleave",()=>{
    if ( !checkUserLoggedIn() ) return;
    accountInfoWrapper.style.display="none";
})


// Game Wrapper Animation scripts......
let gameWrappers = document.querySelectorAll(".sale-wrapper .game-wrapper");
gameWrappers.forEach(gameWrapper => {
    gameWrapper.addEventListener("mouseenter",()=>{
        let overlayWrapper = gameWrapper.querySelector(".overlay-wrapper");
        setTimeout(() => {
            overlayWrapper.style.display = "flex";
        }, 500);
    });
});


// Pagination scripts......
function listenPaginationEvents() {
    let currentPaginationNumber = document.querySelector(".pagination-wrapper .current-pagination-number");
    let hoverablePaginations = document.querySelectorAll(".pagination-wrapper div");
    for ( let count = 0 ; count < hoverablePaginations.length ; count++ ) {
        if ( hoverablePaginations[count].textContent.trim() === currentPaginationNumber.value ) {
            hoverablePaginations[count].classList.add("selected-number");
        }
        hoverablePaginations[count].addEventListener("click",()=>{
            let selectedValue = parseInt(document.querySelector(".pagination-wrapper .selected-number").textContent.trim());
            let paginationValue = hoverablePaginations[count].textContent.trim();
            if ( paginationValue === "<" ) {
                window.location = "index.php?page-number="+(selectedValue-1);
            } else if ( paginationValue === ">" ) {
                window.location = "index.php?page-number="+(selectedValue+1);
            } else if ( paginationValue !== "..." ) {
                window.location = "index.php?page-number="+paginationValue;
            }
        });
    }
}
listenPaginationEvents(); 


// Game details scripts....
let newReleaseGameInfoWrappers = document.querySelectorAll(".latest-games-wrapper .game-info-wrapper");
newReleaseGameInfoWrappers.forEach(infoWrapper => {
    infoWrapper.addEventListener("click",()=>{
        let gameId = infoWrapper.querySelector(".game-id").value;
        window.location = "game-info.php?game-id="+gameId;
    });
})

let browseGameDetailButtons = document.querySelectorAll(".sale-wrapper .game-wrapper .overlay-wrapper .check-game-detail-button");
for ( let count=0 ; count<browseGameDetailButtons.length ; count++ ) {
    browseGameDetailButtons[count].addEventListener("click",()=>{
        let gameId = browseGameDetailButtons[count].getAttribute("game-id");
        window.location = "game-info.php?game-id="+gameId;
    });
}

