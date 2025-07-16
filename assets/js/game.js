import Player from './Player.js';
export default class Game{
    constructor(players){
        this.players = players;
    }
    
    
    addNewGame(playersWinners) {
        console.log("Players",this.players);
        var peticion = new XMLHttpRequest();
        peticion.open("POST", "../php/addNewGame.php", true);  
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Armar los parámetros correctamente

        let param = "countWinners=" + playersWinners.length + "&countPlayers=" + this.players.length;
        playersWinners.forEach((player, i) => {
            param += "&Winner" + i + "=" + encodeURIComponent(player.getId());
        });
        this.players.forEach((player, i) => {
            param += "&Player" + i + "=" + encodeURIComponent(player.getId());
        });
        peticion.onreadystatechange = function() {  
            if (peticion.readyState === 4 && peticion.status === 200) {
                console.log("New game added successfully");
            } 
        }
        console.log("Parametros:", param); // DEBUG
        peticion.send(param);
    }
}