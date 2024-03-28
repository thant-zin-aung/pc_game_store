let relatedGamesDetailButtons = document.querySelectorAll(".related-games-wrapper .game-wrapper .check-game-detail-button");
for ( let count=0 ; count<relatedGamesDetailButtons.length ; count++ ) {
    relatedGamesDetailButtons[count].addEventListener("click",()=>{
        let gameId = relatedGamesDetailButtons[count].getAttribute("game-id");
        window.location = "game-info.php?game-id="+gameId;
    });
}