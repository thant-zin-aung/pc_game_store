let homeTab = document.querySelector("nav .middle-wrapper .home-tab");
let onlineTab = document.querySelector("nav .middle-wrapper .online-tab");
let downloadGuideTab = document.querySelector("nav .middle-wrapper .download-guide-tab");
let contactUsTab = document.querySelector("nav .middle-wrapper .contact-us-tab");
let addToCartWrapper = document.querySelector("nav .add-to-cart-wrapper");
let orderHistoryTab = document.querySelector("nav .right-wrapper .login-signup-wrapper .account-info-wrapper .order-history-tab");
let myGameLibraryTab = document.querySelector(".my-game-library-tab");
let searchBar = document.querySelector("nav .right-wrapper .search-bar-wrapper .search-bar");
let searchIcon = document.querySelector("nav .right-wrapper .search-bar-wrapper .search-icon");
let logoutTab = document.querySelector("nav .right-wrapper .login-signup-wrapper .account-info-wrapper .log-out-tab");
let desktopNavbar = document.querySelector(".desktop-nav");
let mobileMenuButton = document.querySelector("nav .menu-button");
let menuCloseButton = document.querySelector("nav .menu-close-button");



homeTab.addEventListener("click",()=>window.location = "index.php");
onlineTab.addEventListener("click",()=>window.location="search-result.php?game-type=online");
downloadGuideTab.addEventListener("click",()=>window.location="download-guide.php");
contactUsTab.addEventListener("click",()=>window.open("https://www.facebook.com/blackskypcgamestore","_blank"));
// addToCartWrapper.addEventListener("click",()=>window.location="cart.php");

// Genre list navigation scripts....
let genres = document.querySelectorAll("nav .middle-wrapper .genre-tab .genre-wrapper .genre");
genres.forEach(genre=>{
    genre.addEventListener("click",()=>{
        window.location = "search-result.php?genre-id="+genre.getAttribute("genre-id");
    })
})

orderHistoryTab.addEventListener("click",()=>window.location="order-history.php");
myGameLibraryTab.addEventListener("click",()=>window.location="my-game-library.php");
logoutTab.addEventListener("click",event=>window.location = "logout.php");

searchIcon.addEventListener("click",()=>{
    let keyword = searchBar.value.trim();
    if ( keyword !== "" ) {
        window.location = "search-result.php?game-title-keyword="+keyword;
    }
})
searchBar.addEventListener("keyup",event=>{
    let keyword = searchBar.value.trim();
    if ( event.keyCode === 13 && keyword !== "") { // keyCode === 13 means user pressed on enter key...
        window.location = "search-result.php?game-title-keyword="+keyword;
    }
});


mobileMenuButton.addEventListener("click",()=>{
    mobileMenuButton.style.display = "none";
    menuCloseButton.style.display = "block";
    desktopNavbar.setAttribute("style","left: 0;");
});
menuCloseButton.addEventListener("click",()=>{
    menuCloseButton.style.display = "none";
    mobileMenuButton.style.display = "block";
    desktopNavbar.setAttribute("style", "left: -320px");
});