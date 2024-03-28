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


let approveButton = document.querySelector(".order-detail-wrapper .detail-wrapper .list-wrapper .approve-button");
let declineButton = document.querySelector(".order-detail-wrapper .detail-wrapper .list-wrapper .decline-button");
let editButton = document.querySelector(".order-detail-wrapper .detail-wrapper .list-wrapper .edit-button");

editButton.addEventListener("click",()=>{
    approveButton.style.display = "block";
    declineButton.style.display = "block";
    editButton.style.display = "none";
});
