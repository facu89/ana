<?php 
session_start();

$countPlayers = $_SESSION['countPlayers'] ?? 0;
$players = [];

for ($i = 1; $i <= $countPlayers; $i++) {
    $playerSession = $_SESSION['player' . $i] ?? null;
    if ($playerSession) {
        $players[] = [
            'id' => $playerSession['id'],
            'name' => $playerSession['username'],
            'turn' => $_SESSION['turn' . $i]  ?? null,
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($players);
?>
