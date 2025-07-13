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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ana</title>
  <link rel="icon" href="../princesaAna.png" type="image/png"/>
  <link rel="stylesheet" href="../css/lobby.css">
</head>
<body class="lobby">
  <?php
    if (isset($_GET['btnPlay'])){
      header("Location: login.php?selectPlayers=" . urlencode($_GET['selectPlayers']) . "&selectSize=" . urlencode($_GET['selectSize'])."&loginPlayer=2");
      exit;
    }
  ?>
   <header><p class="WelcomeText_1">Bienvenido a Ana</p>
  <div style="position:absolute;top:20px;right:30px;">
    <a href="login.php?reset=1" style="color:#c4b2ff;text-decoration:none;font-size:1rem;">Cerrar sesión</a>
  </div>
</header>
   <p class="WelcomeText_2">Seleccione la configuracion de la partida.</p>

  <div class="lobbyMain">
    <div class="card">
      <div class="cardControls">
        <form action="lobby.php" method="get">
            <p class="cardText">Selecciona el tamaño de la grilla</p>
            <select id="selectSize" class="select" name="selectSize">
            <option value="4">4 × 4</option>
            <option value="6">6 × 6</option>
            <option value="10">10 × 10</option>
            </select>
            <p class="cardText">Selecciona la cantidad de jugadores</p>
            <select id="selectPlayers" class="select" name="selectPlayers">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
            <button  class="btn" type="submit" name="btnPlay">Jugar</button>
        </form>
      </div>
    </div>
  </div>

  <footer class="lobbyFooter">
    <p>Creado por Facundo Vidal · 
      <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
      <div>facundovidal492@gmail.com</div>
    </p>

  </footer>

</body>
</html>