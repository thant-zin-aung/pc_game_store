let posterImage = document.querySelector(".image-and-game-detail-wrapper .image-wrapper .poster-image");
let additionalImages = document.querySelectorAll(".image-and-game-detail-wrapper .image-wrapper .additional-image-wrapper .additional-image");
let linkBox = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .link-box");

linkBox.addEventListener("keyup",()=>{
    changeImage(linkBox.value);
});
function changeImage(imageLinks) {
    let imageLinkArray = imageLinks.split("\n");
    posterImage.src = imageLinkArray[0];
    for ( let count = 0 ; count < additionalImages.length ; count++ ) {
        additionalImages[count].src = imageLinkArray[count+1];
    }
}


// Upload images script...
let gameTitleInput = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .detail-wrapper .value .game-title-input");
let uploadImagesButton = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .link-title .upload-button");
let uploadImagesInput = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .link-title input");
// let uploadStatus = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .link-title .status");
let waitLabel = document.querySelector(".image-and-game-detail-wrapper .game-detail-wrapper .link-title .wait-label");
let IMGBB_API_KEY = "cf29d548b31fbd5e1eed78e2e0763e5a";
let IMGBB_API_ENDPOINT = "https://api.imgbb.com/1/upload";
let imgbb_url_links = [];
let game_titles = [];
let uploadCondition = true;

uploadImagesButton.addEventListener("click",()=>{
    if ( gameTitleInput.value.trim() === "" ) {
        alert("Please enter the game title first...");
        return;
    }
})
uploadImagesInput.addEventListener("change",()=>{
    if ( uploadImagesInput.files.length === 0 ) {
        alert("Please select images first to upload...");
        return;
    } else if ( gameTitleInput.value === "" ) {
        alert("Please enter the game title first...");
        return;
    }
    waitLabel.style.display = "inline-block";
    waitLabel.textContent = "please wait while uploading...";
    waitLabel.style.color="#F47B31";
    for ( let count = 0 ; count < uploadImagesInput.files.length ; count++ ) {
        let imageName = "";
        if ( count === 0 ) {
            imageName = count+"-"+gameTitleInput.value;
        } else {
            imageName = count+"-"+gameTitleInput.value+"-additional-image";
        }
        const formData = new FormData();
        formData.append("key", IMGBB_API_KEY);
        formData.append("name", imageName);
        formData.append("image", uploadImagesInput.files[count]);
        upload_image(formData);
    }
});

async function upload_image(formData) {
    if ( !uploadCondition ) return;
    try {
        const response = await fetch(IMGBB_API_ENDPOINT, {
        method: "POST",
        body: formData,
        });
        const result = await response.json();
        let img_url = result.data.image.url;
        imgbb_url_links.push(img_url);
        if ( imgbb_url_links.length === uploadImagesInput.files.length && uploadCondition ) {
            waitLabel.textContent = "Successfully uploaded...";
            waitLabel.style.color = "#29C93A";
            let final_link_array = link_sorter_from_array_using_filename(imgbb_url_links);
            final_link_array.forEach(url => {
                linkBox.textContent += url+"\n";
            });
            changeImage(linkBox.value);
            imgbb_url_links = [];
            final_link_array = [];
            // gameTitleInput.value = "";
        }
    } catch (error) {
        waitLabel.textContent = "Failed/Error to upload...";
        waitLabel.style.color = "red";
        uploadCondition = false;
        console.log(error);
        return;
    }
}


function link_sorter_from_array_using_filename(linkArray) {
    let filenameArray = [];
    let finalLinkArray = [];
    for ( let count = 0 ; count < linkArray.length ; count++ ) {
        let lastIndexOfLink = linkArray[count].split("/").length-1;
        let filename = linkArray[count].split("/")[lastIndexOfLink];
        filenameArray.push(filename);
    }
    filenameArray.sort();
    for ( let count = 0; count < filenameArray.length ; count++ ) {
        for ( let i = 0 ; i < linkArray.length ; i++ ){
            if ( linkArray[i].includes(filenameArray[count]) ) {
                finalLinkArray.push(linkArray[i]);
                break;
            }
        }
    }
    return finalLinkArray;
}
// Upload images script...