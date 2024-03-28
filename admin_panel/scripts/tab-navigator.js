let dashboardTab = document.querySelector("nav .tab-wrapper .dashboard-tab");
let orderTab = document.querySelector("nav .tab-wrapper .order-tab");
let customerTab = document.querySelector("nav .tab-wrapper .customer-tab");
let gameListTab = document.querySelector("nav .tab-wrapper .game-list-tab");
let adminControlTab = document.querySelector("nav .tab-wrapper .admin-control-tab");
let imageUploaderTab = document.querySelector("nav .tab-wrapper .image-uploader-tab");
let logoutTab = document.querySelector("nav .tab-wrapper .log-out-tab");

dashboardTab.addEventListener("click",()=>window.location="index.php");
orderTab.addEventListener("click",()=>window.location="order.php");
customerTab.addEventListener("click",()=>window.location="customer.php");
gameListTab.addEventListener("click",()=>window.location="game-list.php");
adminControlTab.addEventListener("click",()=>window.location="admin-control-center.php");
imageUploaderTab.addEventListener("click",()=>window.location="image-uploader.php");
logoutTab.addEventListener("click",()=>window.location="logout.php");