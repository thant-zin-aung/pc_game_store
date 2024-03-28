let mainImage = document.querySelector(".game-images-wrapper .main-image");
let thumbnailImages = document.querySelectorAll(".game-images-wrapper .thumbnail-wrapper img");
let trailerWrapper = document.querySelector(".trailer-wrapper");
let trailerButton = document.querySelector(".game-images-wrapper .info-wrapper .button-wrapper .watch-trailer-button");
let trailerIframe = document.querySelector(".trailer-wrapper iframe");
let closeTrailerButton = document.querySelector(".trailer-wrapper .iframe-wrapper .close-button");

function start_thumbnail_click_listener() {
    for ( let count = 0 ; count < thumbnailImages.length ; count++ ) {
        thumbnailImages[count].addEventListener("click",()=>{
            let clickedImgSrc = thumbnailImages[count].src;
            if ( clickedImgSrc === mainImage.src ) return;
            for ( let i = 0 ; i < thumbnailImages.length ; i++ ) {
                thumbnailImages[i].classList.remove("current-image");
            }
            thumbnailImages[count].classList.add("current-image");
            mainImage.style.opacity = 0;
            setTimeout(() => {
                mainImage.src = clickedImgSrc;    
                mainImage.style.opacity = 1;
            }, 200);

        });
    }
}

function start_trailer_listener() {
    trailerButton.addEventListener("click",()=>{
        trailerWrapper.style.display = "flex";
        setTimeout(() => {
            trailerWrapper.style.opacity = 1;
        }, 100);
    });

    closeTrailerButton.addEventListener("click",()=>{
        setTimeout(() => {
            trailerWrapper.style.display="none";
            trailerIframe.src+='';
        }, 500);
        trailerWrapper.style.opacity = 0;
    });
}

start_thumbnail_click_listener();
start_trailer_listener();


// Top related games wrapper scripts...................

var splide = new Splide( '.splide', {
    type   : 'loop',
    drag   : 'free',
    snap   : true,
    perPage: 5,
    pagination: false,
    gap: 20,
  } );
  
splide.mount();