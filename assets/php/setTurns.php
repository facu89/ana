<?php
session_start();

$countPlayers = isset($_POST['countPlayers']) ? intval($_POST['countPlayers']) : 0;
$VARIABLEDECOMPROBACION = [];
for ($i = 0; $i < $countPlayers; $i++) {
    if (isset($_POST['player' . $i])) {
        $_SESSION['turn' . ($i + 1)] = intval($_POST['player' . $i]);
        $VARIABLEDECOMPROBACION[] = $_SESSION['turn' . ($i + 1)];
    }
}
$tempObj = json_encode($VARIABLEDECOMPROBACION);
echo json_encode(['success' => true, 'data' => $tempObj]);
?>