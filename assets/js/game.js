import Player from './player.js';
document.addEventListener("DOMContentLoaded", () => {
    const players = [];
    var letter = 'A';
    const params = new URLSearchParams(window.location.search);
    const countPlayers = params.get("selectPlayers");
    const size = parseInt(params.get("selectSize"));
    var playerOfTurn;
    const btnAbandonner = document.getElementById("btnEndGame");
    btnAbandonner.addEventListener("click", abandonnerTout);
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
                    // Inicializar dependencias de players aquí
                    playerOfTurn = getFirstPlayer();
                    console.log(playerOfTurn);
                    document.getElementById("textSelectedLetter").textContent = "Letra: " + letter;
                    generateTable();
                    uploadResultsDOM();
                    const btnChangeLetter = document.getElementById("btnChangeLetter");
                    btnChangeLetter.addEventListener("click", changeLetter);
                } catch (e) {
                    console.error("Respuesta inválida:", peticion.responseText);
                }
            }
        };
        peticion.send();
    }

    chargerPlayers();

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
                button.className = 'btnBox';
                button.value = '';
                button.textContent = '-';
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
        console.log("termino el juego");
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
        divTextResults.innerHTML = '';
        var div = document.createElement('div');
        div.textContent = 'Turno de: ' + playerOfTurn.getName();
        divTextResults.appendChild(div);
        players.forEach(player => {
            div = document.createElement('div');
            div.className = 'divPlayerResults';
            if (player.getInGame()) {
                div.textContent = 'Puntaje del jugador ' + player.getName() + ': ' + player.getScore();
            }
            else {
                div.textContent = 'El jugador ' + player.getName() + ' ha abandoando.';
            }
            var button = document.createElement('button');
            button.className = 'btn';
            button.textContent = 'Abandonar';
            button.addEventListener('click', function () {
                button.disabled = true;
                player.abandon();
                uploadResultsDOM();
                endGame();
            })
            divTextResults.appendChild(div);
            divTextResults.appendChild(button);
        });
    }
    function getTotalScore() {
        return players.reduce((sum, player) => sum + player.getScore(), 0);
    }
    function abandonnerTout() {
        players.forEach(player => {
            player.abandon();
        });
        uploadResultsDOM();
        endGame();
    }
});
