<?php 

session_start();
if(!isset($_SESSION['player1'])){
        header("Location: login.php");
        exit;
}       
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/game.css">
    <script src="../js/gameFunctionality.js" type="module"></script>
    <title>Ana</title>
    <link rel="icon" href="../princesaAna.png" type="image/png"/>
</head>
<body id="body">
    <?php
    ?>
   <header>A jugar!
<!-- este hidden lo uso para obtener la url y usarla para redireccionar al ranking y obtener los parametros originales por si el usuario selecciona
 volver a jugar --> 
 
  <input type="hidden" id="back" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
 <div class="logout-container">
              <a href="lobby.php?changeTable=1&selectPlayers=<?php echo htmlspecialchars($_GET['selectPlayers'] ?? ''); ?>" class="btn btn-logout">Cambiar tablero</a>

            <a href="login.php?reset=1" class="btn btn-logout">Cerrar sesión</a>
<a href="manual.php?back=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-logout">¿Cómo jugar?</a>   </header>

<div id="mainContainer">
  <div id="gameSection">
    <div id="divGameTable"></div>  

    <div id="sidePanel">
      <div class="divChangeLetter">
        <button id="btnChangeLetter">Cambiar letra</button>
        <div id="textSelectedLetter"></div>
      </div>
    </div>

    <div id="textResults"></div>

    <div id="divAbandonButtonResult">
      <button id="btnEndGame">Abandonar todos</button>
      <div id="result"></div>
    </div>
  </div>
</div>

<footer>
  <p>      Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> · facundovidal492@gmail.com
</p>
</footer>

</body>
</html>