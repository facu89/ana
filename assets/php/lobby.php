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
    if (isset($_GET['changeTable']) &&  isset($_GET['btnPlay'])) {
     
      header("Location: game.php?selectPlayers=" . urlencode($_GET['selectPlayers']) . "&selectSize=" . urlencode($_GET['selectSize']));
      exit;
    }
    if (isset($_GET['btnPlay'])){
      $countPlayers = (int)$_GET['selectPlayers'];
      $_SESSION['countPlayers'] = $countPlayers;
      header("Location: login.php?selectPlayers=" . urlencode($_GET['selectPlayers']) . "&selectSize=" . urlencode($_GET['selectSize'])."&loginPlayer=2");
      exit;
    }
  ?>
   <header><p class="WelcomeText_1">Bienvenido a Ana</p>
<div class="logout-container">
            <a href="login.php?reset=1" class="btn btn-logout">Cerrar sesión</a>
<a href="manual.php?back=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-logout">¿Cómo jugar?</a>    </div>
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
            <?php
            if(!isset($_GET['changeTable'])) {
                          ?>
            <p class="cardText">Selecciona la cantidad de jugadores</p>
            <select id="selectPlayers" class="select" name="selectPlayers">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
          

            <?php 
            }
            if(!isset($_GET['changeTable'])){
            ?>
            <button  class="btn" type="submit" name="btnPlay">Siguiente</button>
            <?php 
            } else{
            ?>
              <input type="hidden" name="selectPlayers" value="<?php echo htmlspecialchars($_GET['selectPlayers'] ?? '');; ?>">
             <input type="hidden" name="changeTable" value="1">
            <button class="btn" type="submit" name="btnPlay">Cambiar tablero</button>
            <?php
            }
            ?>

        </form>
      </div>
    </div>
  </div>

  <footer class="lobbyFooter">
    <p>
      
            Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> · facundovidal492@gmail.com

    </p>

  </footer>

</body>
</html>