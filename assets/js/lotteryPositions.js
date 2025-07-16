import Player from './Player.js';
import Game from './game.js';

document.addEventListener("DOMContentLoaded", () => {
    chargerResults();

    function chargerResults() {
        var peticion = new XMLHttpRequest();
        peticion.open("POST", "../php/getLastGames.php", true);
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var param = "lastWinner=true";
        peticion.onreadystatechange = function() {
            console.log(peticion.responseText); 
            if (peticion.readyState === 4 && peticion.status === 200) {
                //si hay ganadores
                var playersWiner = JSON.parse(peticion.responseText);
                var div = document.getElementById("resultLastGames");
                div.innerHTML = ""; // limpia resultados anteriores
                var p = document.createElement('p');
                if (playersWiner.length > 0) {
                    if (playersWiner.length === 1) {
                        p.textContent = 'Último ganador en la última partida terminada: ';
                        div.appendChild(p);
                        var p2 = document.createElement('p');
                        p2.textContent = playersWiner[0];
                        div.appendChild(p2);
                    } else {
                        p.textContent = 'Últimos ganadores en la última partida terminada: ';
                        div.appendChild(p);
                        var p2 = document.createElement('p');
                        playersWiner.forEach((player) => {
                            var span = document.createElement('span');
                            span.textContent = player;
                            span.classList.add('winner');
                            p2.appendChild(span);
                        });
                        div.appendChild(p2);
                    }
                } else {
                    // si no hay ganadores
                    var peticion2 = new XMLHttpRequest();
                    peticion2.open("POST", "../php/getLastGames.php", true);
                    peticion2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    var param2 = "lastGames=true";
                    peticion2.onreadystatechange = function() {
                        if (peticion2.readyState === 4 && peticion2.status === 200) {
                            var players = JSON.parse(peticion2.responseText);
                            div.innerHTML = "";
                            var p = document.createElement('p');
                            p.textContent = 'No hay ganadores entre ustedes. Cantidad de partidas ganadas por jugador:';
                            div.appendChild(p);
                            var p2 = document.createElement('p');
                            players.forEach((player) => {
                                var span = document.createElement('span');
                                span.textContent = player.username + ': ' + player.gamesWon;
                                span.classList.add('winner');
                                p2.appendChild(span);
                            });
                            div.appendChild(p2);
                        }
                    };
                    peticion2.send(param2);
                }
            }
        };
        peticion.send(param);
    }
    function lotteryPositions(){
        var div = document.getElementById("divDices");
        var dice = document.createElement("div");
        dice.classList.add("dice");
       
    }
    function randomArray() {
        $randomArray=[1,2,3,4,5,6];
        arr.sort(() => Math.random() - 0.5);
    }
         function chargerPlayers() {
                var peticion = new XMLHttpRequest();
                peticion.open("GET", "../php/playersData.php", true);
                peticion.onreadystatechange = function() {
                    if (peticion.readyState === 4) {
                        try {
                            const playersData = JSON.parse(peticion.responseText);
                            playersData.forEach(data => {
                                const player = new Player(data.id, data.name, data.turn);
                                players.push(player);
                            });
                            
                        } catch (e) {
                            console.error("Respuesta inválida:", peticion.responseText);
                        }
                    }
                };
                peticion.send();
            }
});