let detailButtons = document.querySelectorAll(".detail-button");
detailButtons.forEach(detailButton=>{
    detailButton.addEventListener("click",()=>{
        let orderId = detailButton.getAttribute("order-id");
        window.location="order-detail.php?order-id="+orderId;
    });
})