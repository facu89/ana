<?php 
session_start();

$countPlayers = $_SESSION['countPlayers'] ?? 2;
$players = [];

for ($i = 1; $i <= $countPlayers; $i++) {
    $playerSession = $_SESSION['player' . $i] ?? null;
    if ($playerSession) {
        $players[] = [
            'id' => $playerSession['id'],
            'name' => $playerSession['username'],
            'turn' => $i
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($players);
?>
