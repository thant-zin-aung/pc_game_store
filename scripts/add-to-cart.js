let cartTotalGame = document.querySelectorAll("nav .right-wrapper .add-to-cart-wrapper .total-game");
let cartItem = document.querySelector("nav .right-wrapper .add-to-cart-form .cart-item");
let cartItemTitle = document.querySelector(".cart-wrapper .title");
let unitPrice = document.querySelector(".unit-price");
let subTotalHint = document.querySelector(".sub-total-hint");
let subTotalPrice = document.querySelector(".sub-total-price");
let totalPrice = document.querySelector(".total-price");
let placeOrderTotalGameLabel = document.querySelector(".place-order-wrapper .sub-label .total-game-label");
let placeOrderTotalPriceLabel = document.querySelector(".place-order-wrapper .sub-label .total-price-label");
let username = document.querySelector("nav .right-wrapper .login-signup-wrapper .label");
let isUserLoggedIn = document.querySelector(".is-user-logged-in").value.trim() === "yes" ? true : false;


if ( isUserLoggedIn ) {
    syncLocalStorageCartItemWithServerWithoutItemList();
} else {
    deleteCartLocalStorage();
    updateCartStatus();
}

function deleteCartLocalStorage() {
    localStorage.removeItem("blacksky-cart");
}
function getUsername() {
    return username.textContent.trim();
}

function getCartItems() {
    let currentCartArray = JSON.parse(localStorage.getItem("blacksky-cart"));
    return currentCartArray===null ? [] : currentCartArray;
}
function syncLocalStorageCartItemWithServer(cartItemListFromServer) {
    localStorage.setItem("blacksky-cart",JSON.stringify(cartItemListFromServer));
    updateCartStatus();
}
async function syncLocalStorageCartItemWithServerWithoutItemList() {
    let formData = new FormData();
    formData.append("getItem",true);
    formData.append("loginUsername",getUsername());
    try {
        const response = await fetch("add-item-to-cart.php", {
          method: 'POST',
          body: formData
        });
        let responseData = await response.json();
        let cartItemListFromServer = responseData.cart_item_list;
        syncLocalStorageCartItemWithServer(cartItemListFromServer);
    } catch (error) {
        console.log('Error:', error);
    }
}

async function addItemToCart(item) {
    let formData = new FormData();
    formData.append("addItem",true);
    formData.append("gameId",item);
    formData.append("loginUsername",getUsername());
    try {
        const response = await fetch("add-item-to-cart.php", {
          method: 'POST',
          body: formData
        });
        let responseData = await response.json();
        let addItemStatus = responseData.status;
        let cartItemListFromServer = responseData.cart_item_list;
        switch ( addItemStatus ) {
            case "exist":       
                break;
            case "success":
                syncLocalStorageCartItemWithServer(cartItemListFromServer);
                break;
            case "fail":
                break;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}
async function removeItemFromCart(item) {
    let formData = new FormData();
    formData.append("deleteItem",true);
    formData.append("gameId",item);
    formData.append("loginUsername",getUsername());
    try {
        const response = await fetch("add-item-to-cart.php", {
          method: 'POST',
          body: formData
        });
        let responseData = await response.json();
        let addItemStatus = responseData.status;
        let cartItemListFromServer = responseData.cart_item_list;
        switch ( addItemStatus ) {
            case "not_exist":       
                break;
            case "success":
                syncLocalStorageCartItemWithServer(cartItemListFromServer);
                updatePriceStatus();
                updateCartItemStatus();
                break;
            case "fail":
                break;
        }
    } catch (error) {
        console.log('Error:', error);
    }
}
function updateCartStatus() {
    let totalGame = getCartItems().length;
    if ( totalGame !== 0 ) {
        // cartTotalGame.textContent = "("+totalGame+")";
        cartTotalGame.forEach(cartTotal => {
            cartTotal.textContent = "("+totalGame+")";
        })
    } else {
        // cartTotalGame.textContent = "";
        cartTotalGame.forEach(cartTotal => {
            cartTotal.textContent = "";
        })
    }
    cartItem.value = JSON.stringify(getCartItems());
}
function updateCartItemStatus() {
    let totalCartItem = cartItemTitle.querySelector("span");
    totalCartItem.textContent = "("+getCartItems().length+")";
}
function updatePriceStatus() {
    let totalGame = getCartItems().length;
    let actualUnitPrice = parseInt(unitPrice.value);
    let actualSubTotalHint = "("+actualUnitPrice+" x "+totalGame+")";
    let actualSubTotalPrice = actualUnitPrice*totalGame+" MMK";
    let actualTotalPrice = actualUnitPrice*totalGame+" MMK";
    subTotalHint.textContent = actualSubTotalHint;
    subTotalPrice.textContent = actualSubTotalPrice;
    totalPrice.textContent = actualTotalPrice;
    placeOrderTotalGameLabel.textContent = totalGame+" games";
    placeOrderTotalPriceLabel.textContent = actualTotalPrice;
}

let addToCartButtons = document.querySelectorAll(".add-to-cart-button");
function listenAddToCartButtons() {
    addToCartButtons.forEach(addToCartButton=>{
        addToCartButton.addEventListener("click",()=>{
            if ( !isUserLoggedIn ) window.location = "login.php";
            else {
                let gameId = parseInt(addToCartButton.getAttribute("game-id"));
                addItemToCart(gameId);
            }
        });
    });
}

updateCartStatus();
listenAddToCartButtons();