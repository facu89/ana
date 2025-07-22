<?php 
require_once("Game.class.php");
$countPlayers = $_POST['countPlayers'];
$countWinners = $_POST['countWinners'];
$size = $_POST['size'];
$players= [];
$winners = [];
for($i = 0; $i < $countPlayers; $i++){

    $players[] = $_POST['Player' . $i];
}
for($i = 0; $i < $countWinners; $i++){
    $winners[] = $_POST['Winner' . $i];
}
$date = date('Y-m-d H:i:s');    

Game::createGame($players, $winners, $date,$size);    

?>