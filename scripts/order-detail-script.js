let screenshotWrapper = document.getElementsByClassName("screenshot-wrapper")[0];
let screenshotDownloadButton = document.querySelector(".screenshot-wrapper .download-button");
let screenshotCloseButton = document.querySelector(".screenshot-wrapper .close-button");
let screenshotViewButton = document.querySelector(".order-detail-wrapper .detail-wrapper .list-wrapper .detail .value .image-view-button");

screenshotViewButton.addEventListener("click",()=>{
    screenshotWrapper.style.display = "flex";
});
screenshotCloseButton.addEventListener("click",()=>{
    screenshotWrapper.style.display = "none";
});
