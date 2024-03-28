let viewDetailButtons = document.querySelectorAll(".customer-table tr .view-detail-button");
viewDetailButtons.forEach(viewDetailButton=>{
    viewDetailButton.addEventListener("click",()=>{
        let customerId = viewDetailButton.getAttribute("customer-id");
        window.location = "customer-detail.php?customer-id="+customerId;
    })
});