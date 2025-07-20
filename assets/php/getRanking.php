<?php 
require_once('Game.class.php');
header('Content-Type: application/json');
$ranking = Game::getRanking();
$rankingUsers = [];
foreach($ranking as $user){
    $userObj = new stdClass();
    $userObj->username = $user['username'];
    $userObj->gameswon = $user['games_won'];
    $rankingUsers[] = $userObj;
}
echo json_encode($rankingUsers);
?>