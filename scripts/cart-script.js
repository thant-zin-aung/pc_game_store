let removeButtons = document.querySelectorAll(".remove-button");
removeButtons.forEach(removeButton => {
    removeButton.addEventListener("click",()=>{
        let gameId = parseInt(removeButton.getAttribute("game-id"));
        let itemWrapper = removeButton.parentElement.parentElement;
        itemWrapper.style.display = "none";
        removeItemFromCart(gameId);
    });
});

function removeCartItemsFromPage() {
    removeButtons.forEach(removeButton=>{
        removeButton.parentElement.parentElement.style.display = "none";
    })
}

syncLocalStorageCartItemWithServerWithoutItemList();

let checkOutButton = document.querySelector(".cart-item-list-wrapper .price-info-wrapper .checkout-button");
let placeOrderOverlayWrapper = document.querySelector(".place-order-overlay-wrapper");
let orderConfirmOverlayWrapper = document.querySelector(".order-confirm-overlay-wrapper");
let orderConfirmWrapper = document.querySelector(".order-confirm-wrapper");
let backToHomeButton = document.querySelector(".order-confirm-wrapper .home-button");
let enlargeOrderConfirmWrapperClassName  = "enlarge-order-confirm-wrapper";
let closeIcon = document.querySelector(".place-order-wrapper .close-icon");
checkOutButton.addEventListener("click",()=>{
    if (getCartItems().length > 0 ) {
        placeOrderOverlayWrapper.style.display = "flex";
    }
});
closeIcon.addEventListener("click",()=>{
    placeOrderOverlayWrapper.style.display = "none";
});

let screenshotInputFile = document.querySelector(".place-order-wrapper .payment-proof-wrapper .screenshot-wrapper input");
let screenshotLabel = document.querySelector(".place-order-wrapper .payment-proof-wrapper .screenshot-wrapper label");
screenshotInputFile.addEventListener("change",()=>{
    screenshotLabel.querySelector("i").style.display="none";
    screenshotLabel.querySelector(".hint-label").style.display="none";
    let imageFileUrl = URL.createObjectURL(screenshotInputFile.files[0]);
    screenshotLabel.setAttribute("style","background-image: url('"+imageFileUrl+"')");
});

let transactionIdInput = document.querySelector(".place-order-wrapper .payment-proof-wrapper .transaction-id-wrapper input");
let placeOrderButton = document.querySelector(".place-order-button");
let payemntOptions = document.querySelectorAll(".place-order-wrapper .receiver-wrapper .payment-wrapper .payment .payment-option");
payemntOptions.forEach(paymentOption => {
    paymentOption.addEventListener("click",()=>{
        payemntOptions.forEach(option=>{
            option.checked = false;
        })
        paymentOption.checked = true;
    });
});
placeOrderButton.addEventListener("click",()=>{
    let isPaymentOptionSelected;
    let paymentType;
    payemntOptions.forEach(option=>{
        if ( option.checked ) {
            isPaymentOptionSelected=true;
            if ( option.classList.contains("kbzpay") ) paymentType="kbzpay";
            if ( option.classList.contains("wavepay") ) paymentType="wavepay";
        }
    })
    let isScreenshotEmpty = screenshotInputFile.files.length === 0;
    let isTransactionIdEmpty = ( transactionIdInput.value === null || transactionIdInput.value === "" );
    if ( isScreenshotEmpty && isTransactionIdEmpty ) {
        alert("Please upload payment screenshot or enter transaction id manually.\n"+
        "---------------------\n"+
        "ငွေလွှဲ screenshot အား upload တင်ပေးပါ။ (သို့မဟုတ်) Transaction id အား manual ရိုက်ထည့်ပေးပါ။");
    } else if ( !isPaymentOptionSelected ) {
        alert("Please select at least one payment method.\n"+
        "---------------------\n"+
        "ကျေးဇူးပြုပြီး ငွေပေးချေမှုစနစ်တခုအား ရွေးချယ်ပေးပါရန်");
    } else {
        placeOrderOverlayWrapper.style.display = "none";
        let transactionId = transactionIdInput.value.trim();
        async function placeOrder() {
            let formData = new FormData();
            formData.append("place_order",true);
            formData.append("loginUsername",getUsername());
            formData.append("paymentType",paymentType);
            formData.append("transactionId",transactionId);
            if ( isScreenshotEmpty ) formData.append("noScreenshot",true);
            else formData.append("uploadedScreenshot",screenshotInputFile.files[0]);
            
            try {
                const response = await fetch("place-order.php", {
                  method: 'POST',
                  body: formData
                });
                let responseData = await response.json();
                let placeOrderStatus = responseData.status.trim();
                if ( placeOrderStatus === "success" ) {
                    await syncLocalStorageCartItemWithServerWithoutItemList();
                    updateCartItemStatus();
                    updatePriceStatus();
                    removeCartItemsFromPage();
                    orderConfirmOverlayWrapper.style.display = "flex";
                    setTimeout(() => {
                        orderConfirmWrapper.classList.add(enlargeOrderConfirmWrapperClassName);
                    }, 50);
                }
                
            } catch (error) {
                console.log('Error:', error);
            }
        }
        placeOrder();
    }
});

backToHomeButton.addEventListener("click",()=>{
    window.location = "index.php";
})
