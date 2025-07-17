import Player from './Player.js';

document.addEventListener("DOMContentLoaded", async () => {
    chargerResults();
    const players = await chargerPlayers();
    uploadDOM();
    const turns = [];

    function uploadDOM(){
        var divDices = document.getElementById("divDices");
        divDices.innerHTML = ""; 
        for (let i = 0; i < players.length; i++) {
            var diceImage = document.createElement("div");
            var namePlayer = document.createElement("p");
            var div = document.createElement('div');
            div.className = "dicePlayer";
            namePlayer.textContent = players[i].getName();
            diceImage.classList.add("diceImage");
            let imgDice = document.createElement('img');
            imgDice.className = 'imgDice';
            imgDice.src = '../dado1.png';
            diceImage.appendChild(imgDice);
            div.appendChild(diceImage);
            div.appendChild(namePlayer);
            divDices.appendChild(div);
        }
    }

    // Agrega el texto de estado arriba de los dados
    let statusText = document.createElement('div');
    statusText.id='statusText';
    
    let divDices = document.getElementById("divDices");
    divDices.parentNode.insertBefore(statusText, divDices);

    const btnLottery = document.getElementById("btnLotteryPositions");
    btnLottery.addEventListener("click",function(){
            lotteryDices();
        document.getElementById("btnPlayGame").disabled = false;
    });

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
                        playersWiner = JSON.parse(peticion.responseText);
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
                    p.textContent = 'No hubo ganadores en la última partida.';
                    div.appendChild(p);
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

    function resolveTies(turns) {
        const groups = {};
        turns.forEach(turn => {
            if (!groups[turn.number]) groups[turn.number] = [];
            groups[turn.number].push(turn);
        });

        let hasTie = false;
        let tieGroups = [];
        Object.values(groups).forEach(group => {
            if (group.length > 1) {
                hasTie = true;
                tieGroups.push(group);
            }
        });

        if (hasTie) {
            statusText.textContent = "Sorteando empates...";
            setTimeout(() => {
                tieGroups.forEach(group => {
                    let newNumbers = [];
                    for (let i = 1; i <= group.length; i++) newNumbers.push(i);
                    newNumbers.sort(() => Math.random() - 0.5);

                    group.forEach((turn, idx) => {
                        let dice = document.querySelectorAll(".diceImage")[turn.playerIndex];
                        let imgDice = dice.querySelector('img');
                        if (!imgDice) {
                            imgDice = document.createElement('img');
                            imgDice.className = 'imgDice';
                            dice.appendChild(imgDice);
                        }
                        let rollCount = 10;
                        let roll = 0;
                        let interval = setInterval(() => {
                            imgDice.src = '../dado' + (Math.floor(Math.random() * 6) + 1) + '.png';
                            roll++;
                            if (roll >= rollCount) {
                                clearInterval(interval);
                                turn.number = newNumbers[idx];
                                imgDice.src = '../dado' + newNumbers[idx] + '.png';
                                if (group.every(t => t.number !== undefined)) {
                                    setTimeout(() => {
                                        resolveTies(turns);
                                    }, 500);
                                }
                            }
                        }, 60);
                    });
                });
            }, 700);
            return;
        }

        statusText.textContent = "¡Turnos sorteados!";
        const groupTurns = turns.slice().sort((a, b) => b.number - a.number);
        console.log("Orden final:", groupTurns);

        // Solo aquí:
        chargerTurn();
    }

    function lotteryDices() {
        var diceImages = document.querySelectorAll(".diceImage");
        turns.length = 0; 
        statusText.textContent = "Sorteando turnos...";
        for (let j = 0; j < diceImages.length; j++) {
            let randomNumbers = randomArray();
            let dice = diceImages[j];
            let imgDice = dice.querySelector('img');
            if (!imgDice) {
                imgDice = document.createElement('img');
                imgDice.className = 'imgDice';
                dice.appendChild(imgDice);
            }
            for (let i = 0; i < randomNumbers.length; i++) {
                setTimeout(() => {
                    imgDice.src = '../dado' + randomNumbers[i] + '.png';
                    if (i === randomNumbers.length - 1) {
                        turns[j] = {
                            playerIndex: j,
                            number: randomNumbers[i]
                        };
                        if (turns.filter(Boolean).length === diceImages.length) {
                            setTimeout(() => {
                                resolveTies(turns);
                            }, 500);
                        }
                    }
                }, i * 200);
            }
        }
                 chargerTurn();

    }

    function randomArray(){
        var array = [1,2,3,4,5,6];
        return array.sort(() => Math.random() - 0.5);
    }
    function chargerTurn(){
        var peticion = new XMLHttpRequest();
        peticion.open("POST", "../php/setTurns.php", true);
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        const orderedTurns = turns.slice().sort((a, b) => b.number - a.number);
        orderedTurns.forEach((turn, idx) => {
            turn.number = idx + 1;
        });
        orderedTurns.forEach(orderedTurn => {
            const original = turns.find(t => t.playerIndex === orderedTurn.playerIndex);
            if (original) {
                original.number = orderedTurn.number;
            }
        });
        let params = "countPlayers=" + turns.length;
        console.log("Turnos:", turns); // DEBUG
        turns.forEach(turn => {
            params += '&player' + turn.playerIndex + '=' + turn.number;
        });

        peticion.onreadystatechange = function() {
            console.log("aa");
            if (peticion.readyState === 4 && peticion.status === 200) {
                console.log("Turnos guardados correctamente");
                var debugVariable = JSON.parse(peticion.responseText);
                console.log("Respuesta del servidor:", VARIABLEDEPRUEBA);
            } else if (peticion.readyState === 4) {
                console.error("Error al guardar los turnos:", peticion.statusText);
            }
        };
        console.log("Parámetros enviados:", params); // DEBUG
        peticion.send(params);
    }
});