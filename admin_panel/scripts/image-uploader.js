let imagesInput = document.querySelector(".uploader-wrapper .left-wrapper .input-wrapper input");
let imagesInputLabel = document.querySelector(".uploader-wrapper .left-wrapper .input-wrapper label");
let gameTitleInput = document.querySelector(".uploader-wrapper .left-wrapper .game-title-box");
let uploadImageButton = document.querySelector(".uploader-wrapper .left-wrapper .upload-button");
let waitLabel = document.querySelector(".uploader-wrapper .left-wrapper .wait-label");
let textArea = document.querySelector(".uploader-wrapper .right-wrapper textarea");
let IMGBB_API_KEY = "cf29d548b31fbd5e1eed78e2e0763e5a";
let IMGBB_API_ENDPOINT = "https://api.imgbb.com/1/upload";
let imgbb_url_links = [];
let game_titles = [];
let uploadCondition = true;


imagesInput.addEventListener("change",()=>{
    if ( imagesInput.files.length === 0 ) {
        imagesInputLabel.textContent = "Select image files...";
    } else {
        imagesInputLabel.textContent = imagesInput.files.length + " files selected...";
    }
});

uploadImageButton.addEventListener("click",()=>{
    if ( imagesInput.files.length === 0 ) {
        alert("Please select images first to upload...");
        return;
    } else if ( gameTitleInput.value === "" ) {
        alert("Please enter the game title...");
        return;
    }
    waitLabel.style.display = "inline-block";
    for ( let count = 0 ; count < imagesInput.files.length ; count++ ) {
        let imageName = "";
        if ( count === 0 ) {
            imageName = count+"-"+gameTitleInput.value;
        } else {
            imageName = count+"-"+gameTitleInput.value+"-additional-image";
        }
        const formData = new FormData();
        formData.append("key", IMGBB_API_KEY);
        formData.append("name", imageName);
        formData.append("image", imagesInput.files[count]);
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
        console.log(result);
        let img_url = result.data.image.url;
        imgbb_url_links.push(img_url);
        if ( imgbb_url_links.length === imagesInput.files.length && uploadCondition ) {
            waitLabel.textContent = "Successfully uploaded...";
            waitLabel.style.color = "green";
            let final_link_array = link_sorter_from_array_using_filename(imgbb_url_links);
            final_link_array.forEach(url => {
                textArea.textContent += url+"\n";
            })
            imgbb_url_links = [];
            final_link_array = [];
            gameTitleInput.value = "";
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