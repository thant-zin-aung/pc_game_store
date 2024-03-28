let searchResultGameDetailButtons = document.querySelectorAll(".sale-wrapper .game-wrapper .check-game-detail-button");
for ( let count=0 ; count<searchResultGameDetailButtons.length ; count++ ) {
    searchResultGameDetailButtons[count].addEventListener("click",()=>{
        let gameId = searchResultGameDetailButtons[count].getAttribute("game-id");
        window.location = "game-info.php?game-id="+gameId;
    });
}


function listenPaginationEvents() {
    let currentPaginationNumber = document.querySelector(".pagination-wrapper .current-pagination-number");
    let urlVariable = document.querySelector(".pagination-wrapper .url-variable").value;
    let hoverablePaginations = document.querySelectorAll(".pagination-wrapper div");
    for ( let count = 0 ; count < hoverablePaginations.length ; count++ ) {
        if ( hoverablePaginations[count].textContent.trim() === currentPaginationNumber.value ) {
            hoverablePaginations[count].classList.add("selected-number");
        }
        hoverablePaginations[count].addEventListener("click",()=>{
            let selectedValue = parseInt(document.querySelector(".pagination-wrapper .selected-number").textContent.trim());
            let paginationValue = hoverablePaginations[count].textContent.trim();
            if ( paginationValue === "<" ) {
                window.location = "search-result.php?"+urlVariable+"&page-number="+(selectedValue-1);
            } else if ( paginationValue === ">" ) {
                window.location = "search-result.php?"+urlVariable+"&page-number="+(selectedValue+1);
            } else if ( paginationValue !== "..." ) {
                window.location = "search-result.php?"+urlVariable+"&page-number="+paginationValue;
            }
        });
    }
}
listenPaginationEvents(); 