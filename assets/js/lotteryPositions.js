import Player from './Player.js';

document.addEventListener("DOMContentLoaded", async () => {
    chargerResults();
    const players = await chargerPlayers();
    uploadDOM();
    const turns = [];

    function uploadDOM(){
        document.getElementById('btnPlayGame').className = 'disabledBtn';
        var divDices = document.getElementById("divDices");
        divDices.innerHTML = ""; 
        for (let i = 0; i < players.length; i++) {
            var diceImage = document.createElement("div");
            var namePlayer = document.createElement("p");
            var div = document.createElement('div');
            div.className = "dicePlayer";
            namePlayer.textContent = players[i].getName();
            diceImage.classList.add("diceImage");
            div.appendChild(diceImage);
            div.appendChild(namePlayer);
               
       
            let circle = document.createElement('div');
            circle.className = 'turnCircle';
             var posDiv = document.createElement('div');
                posDiv.className = 'playerPosition';
                diceImage.appendChild(posDiv);
            diceImage.appendChild(circle);
        
            divDices.appendChild(div);
        }
    }

    let statusText = document.createElement('div');
    statusText.id='statusText';
    let divDices = document.getElementById("divDices");
    divDices.parentNode.insertBefore(statusText, divDices);

    const btnLottery = document.getElementById("btnLotteryPositions");
    btnLottery.addEventListener("click",function(){
        lotteryDices();
      

    });

    function lotteryDices() {
   
                statusText.textContent = "Sorteando turnos...";

        setTimeout(() => {
                 var diceImages = document.querySelectorAll(".diceImage");
        diceImages.forEach(diceImage => {
            diceImage.innerHTML = '';
        });
        turns.length = 0;
        let uniqueNumbers = [];
        for (let i = 1; i <= diceImages.length; i++) uniqueNumbers.push(i);
        uniqueNumbers.sort(() => Math.random() - 0.5);

        
        for (let j = 0; j < diceImages.length; j++) {
            let dice = diceImages[j];

           
            let circle = document.createElement('div');
            circle.className = 'turnCircle';
            circle.textContent = uniqueNumbers[j];
            dice.appendChild(circle);

            let dicePlayerDiv = dice.parentElement;
            let posDiv = dicePlayerDiv.querySelector('.playerPosition');
            if (!posDiv) {
                posDiv = document.createElement('div');
                posDiv.className = 'playerPosition';
                dicePlayerDiv.insertBefore(posDiv, dicePlayerDiv.firstChild);
            }
            posDiv.textContent = "Puesto: " + uniqueNumbers[j];

            turns[j] = {
                playerIndex: j,
                number: uniqueNumbers[j]
            };
        }

        statusText.textContent = "¡Turnos sorteados!";
        chargerTurn();
          document.getElementById("btnPlayGame").disabled = false;
        document.getElementById('btnPlayGame').className = 'btn';
        }, 3000);

    }

    function chargerResults() {
        var peticion = new XMLHttpRequest();
        peticion.open("POST", "../php/getLastGames.php", true);
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var param = "lastWinner=true";
        peticion.onreadystatechange = function() {
            if (peticion.readyState === 4 && peticion.status === 200) {
                var playersWiner = [];
                if (peticion.responseText) {
                    try {
                        var data  = JSON.parse(peticion.responseText);
                        console.log(data);
                        playersWiner = data.playerWinnersNames;
                        var date = new Date(data.date);
                    } catch (e) {
                        playersWiner = [];
                    }
                }
                var div = document.getElementById("resultLastGames");
                div.innerHTML = "";
                var p = document.createElement('p');
                if (playersWiner.length > 0) {
                    if (playersWiner.length === 1) {
                        p.textContent = 'Último ganador entre ustedes: ';
                        div.appendChild(p);
                
                        var p2 = document.createElement('p');
                        p2.textContent = playersWiner[0];
                        div.appendChild(p2);
                        var p3 = document.createElement('p');
                        const dia = String(date.getDate()).padStart(2, '0');
                        const mes = String(date.getMonth() + 1).padStart(2, '0'); // +1 porque enero es 0
                        const anio = date.getFullYear();
                        p3.textContent = 'Fecha del juego ' + dia + '/' + mes + '/' + anio;
                        div.appendChild(p3);

                    } else {
                        p.textContent = 'Últimos ganadores entre ustedes: ';
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
                     var secondPeticion = new XMLHttpRequest();
                        secondPeticion.open("POST", "../php/getLastGames.php", true);
                        secondPeticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        var param = "lastGames=true";
                        secondPeticion.onreadystatechange = function() {
                         if (secondPeticion.readyState === 4 && secondPeticion.status === 200) {
                            var playersGames = JSON.parse(secondPeticion.responseText); 
                            p.textContent = 'Partidas ganadas:';
                            div.appendChild(p);
                            playersGames.forEach(player => {
                                p = document.createElement('p');
                                p.textContent = player.username + ": " + player.gamesWon;
                                div.appendChild(p);
                            });
                        }
                        }
                        secondPeticion.send(param)
                }
            }
        };
        peticion.send(param);
    }

    function chargerPlayers() {
        return new Promise((resolve, reject) => {
            var players = [];
            var peticion = new XMLHttpRequest();
            peticion.open("GET", "../php/playersData.php", true);
            peticion.onreadystatechange = function() {
                if (peticion.readyState === 4 && peticion.status === 200) {
                    try {
                        const playersData = JSON.parse(peticion.responseText);
                        playersData.forEach(data => {
                            const player = new Player(data.id, data.name, data.turn);
                            players.push(player);
                        });
                        resolve(players);
                    } catch (e) {
                        console.error("Respuesta inválida:", peticion.responseText);
                        resolve([]);
                    }
                }
            };
            peticion.send();
        });
    }

    function chargerTurn(){
        var peticion = new XMLHttpRequest();
        peticion.open("POST", "../php/setTurns.php", true);
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let params = "countPlayers=" + turns.length;
        turns.forEach(turn => {
            params += '&player' + turn.playerIndex + '=' + turn.number;
        });

        peticion.onreadystatechange = function() {
            if (peticion.readyState === 4 && peticion.status === 200) {
                console.log("Turnos guardados correctamente");
            } else if (peticion.readyState === 4) {
                console.error("Error al guardar los turnos:", peticion.statusText);
            }
        };
        peticion.send(params);
    }
});