<?php
session_start();
header('Content-Type: application/json');
require_once('Game.class.php');
require_once('User.class.php'); 

// Si se quiere obtener los últimos ganadores
if (isset($_POST['lastWinner'])) { 
    $players = [];
    for ($i = 1; $i <= (int)$_SESSION['countPlayers']; $i++) {
        if (isset($_SESSION['player' . $i])) {
            $players[] = (int)$_SESSION['player' . $i]['id'];
        }
    }
    $playerWinnersID = Game::getIDLastWinners($players);
    $date = $playerWinnersID->date ?? '';
    $IDWinners = $playerWinnersID->IDwinners ?? [];
    $playerWinnersNames = [];
    foreach ($IDWinners as $id) {
        foreach ($_SESSION as $key => $value) {
            if (is_array($value) && isset($value['id']) && $value['id'] === $id) {
                $playerWinnersNames[] = $value['username'];
            }
        }
    }
    $data = new stdClass();
    $data->playerWinnersNames = $playerWinnersNames;
    $data->date = $date;
    echo json_encode($data); 
    exit;
}

if (isset($_POST['lastGames'])) {
    //para obtener la cantidad de partidas ganadas porcada jugador
    $players = [];
    for ($i = 1; $i <= (int)$_SESSION['countPlayers']; $i++) {
        if (isset($_SESSION['player' . $i])) {
            $players[] = $_SESSION['player' . $i];
        }
    }
    $gamesWonForPlayer = [];
    foreach ($players as $player) {
        $playerWons = new stdClass();
        $playerWons->id = $player['id'];
        $playerWons->username = $player['username'];
        $playerWons->gamesWon = User::getGamesWonByUser((int)$player['id']);
        $gamesWonForPlayer[] = $playerWons;
    }
    echo json_encode($gamesWonForPlayer);
    exit;
}
//por las dudas devuelvo array vacio si no se cumplen las condiciones
echo json_encode([]);
?>