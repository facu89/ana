<?php
session_start();
if(!isset($_SESSION['player1'])){
        header("Location: login.php");
        exit;
}  
if(isset($_POST['btnPlayGames']) && isset($_POST['countPlayers']) && isset($_POST['selectSize'])) {
    $countPlayers = (int)$_POST['countPlayers'];
    $size = (int)($_POST['selectSize'] ?? 3);
        header("Location: game.php?selectPlayers=$countPlayers&selectSize=$size");
    exit;

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana</title>
    <link rel="icon" href="../princesaAna.png" type="image/png"/>
    <script src="../js/lotteryPositions.js" type="module"></script>
    <link rel="stylesheet" href="../css/lotteryPositions.css">
</head>
<body>
    <header><p class="WelcomeText_1">Bienvenidos a Ana</p></header>
    <div class="logout-container">
            <a href="login.php?reset=1" class="btn btn-logout">Cerrar sesión</a>
<a href="manual.php?back=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-logout">¿Cómo jugar?</a>
    </div>
    <div id="resultLastGames"></div>
    <p class="WelcomeText_2">Antes de jugar, deben sortarse las posiciones de los jugadores.</p>
    <p class="WelcomeText_3">Presione el botón para sortear las posiciones.</p>
    <div id="divDices"></div>

    <button id="btnLotteryPositions" class="btn">Sortear posiciones</button>
    <form action="lotteryPositions.php" method="post">
        <input type="hidden" name="countPlayers" value="<?= htmlspecialchars($_GET['selectPlayers'] ?? 0) ?>">
        <input type="hidden" name="selectSize" value="<?= htmlspecialchars($_GET['selectSize'] ?? 0) ?>">
        <button id="btnPlayGame" class="btn" disabled="true" type="submit" name="btnPlayGames">Jugar</button>

    </form>

    <footer class="LotteryFooter">
    <p>Creado por Facundo Vidal · 
      <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
      <div>facundovidal492@gmail.com</div>
    </p>
  </footer>
</body>

</html>