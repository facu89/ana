import Player from './Player.js';
import Game from './game.js';

document.addEventListener("DOMContentLoaded", async () => {
    const players = await chargerPlayers();
    //esto lo hago para que no se cargue hasta que no esten los jugadores

    var letter = 'A';
    const params = new URLSearchParams(window.location.search);
    const countPlayers = params.get("selectPlayers");
    const size = parseInt(params.get("selectSize"));
    let play = new Game(players,size);

    var playerOfTurn;
    var gameEnded = false;
    const btnAbandonner = document.getElementById("btnEndGame");
    btnAbandonner.addEventListener("click", abandonnerTout);
    playerOfTurn = getFirstPlayer();
    console.log(playerOfTurn);
    document.getElementById("textSelectedLetter").textContent = "Letra: " + letter;
    generateTable();
    uploadResultsDOM();
    const btnChangeLetter = document.getElementById("btnChangeLetter");
    btnChangeLetter.addEventListener("click", changeLetter);

    function chargerPlayers() {
        //esto lo hago para que no se cargue el juego hasta que no 
        //esten listos los jugadores
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
                        console.log("Jugadores cargados:", players);
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

    function generateTable() {
        const divTable = document.getElementById("divGameTable");
        divTable.style.setProperty("--n", size);
        divTable.style.height = (40 * size) + "px";
        divTable.style.width = (40 * size) + "px";
        for (let i = 0; i < size; i++) {
            for (let i = 0; i < size; i++) {
                let box = document.createElement('div');
                box.className = 'divBoxGame';
                let button = document.createElement('button');
                button.value = '';
                button.textContent = '-';
                button.classList.add('btnBox', 'btnGameBox');
                button.addEventListener("click", () => {
                    button.textContent = letter;
                    button.disabled = true;
                    highlightANA();
                }
                );
                box.appendChild(button);
                divTable.appendChild(box);
            }
        }
    }
    function changeLetter() {
        console.log("changeLetter");
        if (letter == 'A') {
            letter = 'N';
        }
        else {
            letter = 'A';
        }
        document.getElementById("textSelectedLetter").textContent = "Letra: " + letter;
    }
    function highlightANA() {
        const divTable = document.getElementById("divGameTable");
        const buttons = Array.from(divTable.querySelectorAll("button"));
        const boardBtn = [];
        for (let r = 0; r < size; r++) {
            boardBtn[r] = [];
            for (let c = 0; c < size; c++) {
                boardBtn[r][c] = buttons[r * size + c];
            }
        }
        let count = 0;
        const occurrences = [];
        //  Horizontal
        for (let r = 0; r < size; r++) {
            for (let c = 0; c + 2 < size; c++) {
                const b0 = boardBtn[r][c], b1 = boardBtn[r][c + 1],
                    b2 = boardBtn[r][c + 2];
                if (b0.textContent === 'A' && b1.textContent === 'N' && b2.textContent === 'A'
                ) {
                    count++;
                    occurrences.push([b0, b1, b2]);
                }
            }
        }
        // Vertical
        for (let c = 0; c < size; c++) {
            for (let r = 0; r + 2 < size; r++) {
                const b0 = boardBtn[r][c], b1 = boardBtn[r + 1][c], b2 = boardBtn[r + 2][c];
                if (
                    b0.textContent === 'A' && b1.textContent === 'N' && b2.textContent === 'A'
                ) {
                    count++;
                    occurrences.push([b0, b1, b2]);
                }
            }
        }
        for (let r = 0; r + 2 < size; r++) {
            for (let c = 0; c + 2 < size; c++) {
                const b0 = boardBtn[r][c], b1 = boardBtn[r + 1][c + 1],
                    b2 = boardBtn[r + 2][c + 2];
                if (
                    b0.textContent === 'A' && b1.textContent === 'N' && b2.textContent === 'A'
                ) {
                    count++;
                    occurrences.push([b0, b1, b2]);
                }
            }
        }
        // Diagonal der a izq
        for (let r = 0; r + 2 < size; r++) {
            for (let c = 2; c < size; c++) {
                const b0 = boardBtn[r][c], b1 = boardBtn[r + 1][c - 1],
                    b2 = boardBtn[r + 2][c - 2];
                if (
                    b0.textContent === 'A' && b1.textContent === 'N' && b2.textContent === 'A'
                ) {
                    count++;
                    occurrences.push([b0, b1, b2]);
                }
            }
        }
        //  Limpio cualquier resaltado previo
        buttons.forEach(btn => btn.classList.remove("highlight"));
        // Aplico la clase a cada botón de cada ocurrencia
        occurrences.forEach(triple => {
            triple.forEach(btn => btn.classList.add("highlight"));
        });

        if (occurrences.length - getTotalScore() == 0) {
            playerOfTurn = getNextPlayer();
        }
        else {
            playerOfTurn.addPoints(occurrences.length - getTotalScore());

        }
        uploadResultsDOM();
        if (!buttons.some(btn => btn.textContent === '-')) {
            endGame();
        }
        console.log("ANA totales:", count);
        return count;
    }
    function endGame() {
        var playersInGame = players.filter(player => player.getInGame());
        if (playersInGame.length > 0) {
            // Encuentra el puntaje máximo
            let maxScore = Math.max(...playersInGame.map(p => p.getScore()));
            // Filtra los ganadores
            let playersWinners = playersInGame.filter(p => p.getScore() === maxScore);
                    play.addNewGame(playersWinners);

            if (playersWinners.length > 1) {
                var textResult = 'Los jugadores: ';
                playersWinners.forEach(player => {
                    textResult += player.getName() + ', ';
                });
                textResult = textResult.slice(0, -2);
                document.getElementById("result").textContent = textResult + " han ganado la partida.";
            } else {
                document.getElementById("result").textContent = "El jugador " + playersWinners[0].getName() + " ha ganado la partida.";
            }
        } else {
            document.getElementById("result").textContent = "Todos han abandonado, no hay ganadores en la partida.";
        }
        //obtengo el url original de un hidden que lo gurada en la pagina
        window.location.href = 'ranking.php?selectSize='+size+'&selectPlayers='+countPlayers+'&back=1';

        console.log("termino el juego");
        gameEnded = true;
        // Deshabilitar todos los botones de la tabla de juego
        const boxButtons = Array.from(document.getElementsByClassName('btnGameBox'));
        boxButtons.forEach(button => {
            button.disabled = true;
        });
    }
    function getFirstPlayer() {
        var firstPlayer;
        players.forEach(player => {
            if (player.getTurn() == 1) {
                firstPlayer = player;
            }
        });
        return firstPlayer;
    }
    function getNextPlayer() {
        let nextTurn = playerOfTurn.getTurn();
        let nextPlayer;
        do {
            nextTurn = nextTurn % countPlayers + 1;
            nextPlayer = players.find(p => p.getTurn() === nextTurn);
        } while (!nextPlayer.getInGame());
        console.log("Siguiente jugador:", nextPlayer.getName());
        return nextPlayer;
    }
    function uploadResultsDOM() {
        const divTextResults = document.getElementById('textResults');
        const divPlayersButtons = document.createElement( 'div');
        divPlayersButtons.className = 'divPlayersButtons';
        divTextResults.innerHTML = '';
        // Verifica si hay jugadores en juego
        if (players.some(p => p.getInGame())) {
            var div = document.createElement('div');
            div.textContent = 'Turno de: ' + playerOfTurn.getName();
            divTextResults.appendChild(div);
        } else {
            var div = document.createElement('div');
            div.textContent = 'La partida ha terminado.';
            divTextResults.appendChild(div);
        }
        players.forEach(player => {
            div = document.createElement('div');
            div.className = 'divPlayerResults';
            if (player.getInGame()) {
                div.textContent = 'Puntaje del jugador ' + player.getName() + ': ' + player.getScore();
            }
            else {
                div.textContent = 'El jugador ' + player.getName() + ' ha abandoando.';
            }
            // Solo crear el botón si el juego no terminó y el jugador está en juego
            var divContainer = document.createElement('div');
            var divContainerButton = document.createElement('div');
            divContainerButton.className = 'divContainerButton';
            if (!gameEnded && player.getInGame()) {
                var button = document.createElement('button');
                button.className = 'btn';
                button.textContent = 'Abandonar';
                button.addEventListener('click', function () {
                    if (gameEnded) return;
                    button.disabled = true;
                    player.abandon();
                    uploadResultsDOM();
                    var playersInGame = players.filter(p => p.getInGame());
                    if(playersInGame.length<2){
                        endGame();

                    }

                    console.log(playersInGame.length);
                });
                divContainer.className = 'divPlayerButton';
                divContainer.appendChild(div);
                divContainerButton.appendChild(button);
                divContainer.appendChild(divContainerButton);

                divPlayersButtons.appendChild(divContainer);
                divTextResults.appendChild(divPlayersButtons);
            } else {
                divContainer.className = 'divPlayerButton';
                divContainer.appendChild(div);
                                divContainer.appendChild(divContainerButton);

                divPlayersButtons.appendChild(divContainer);

                divTextResults.appendChild(divPlayersButtons);

            }
        });
    }
    function getTotalScore() {
        return players.reduce((sum, player) => sum + player.getScore(), 0);
    }
    function abandonnerTout() {
        if (gameEnded) return; // Evita acciones si el juego terminó
        players.forEach(player => {
            player.abandon();
        });
        uploadResultsDOM();
        endGame();
    }
});
