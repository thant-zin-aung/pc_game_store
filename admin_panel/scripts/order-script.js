let viewOrderDetailButtons = document.querySelectorAll(".order-table tr .view-detail-button");
viewOrderDetailButtons.forEach(viewOrderDetailButton => {
    viewOrderDetailButton.addEventListener("click",()=>{
        let orderId = parseInt(viewOrderDetailButton.getAttribute("order-id"));
        window.location = "order-detail.php?order-id="+orderId;
    });
})