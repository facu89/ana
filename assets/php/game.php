<?php 

session_start();
if(!isset($_SESSION['player1'])){
        header("Location: login.php");
        exit;
}       
?>
<!DOCTYPE html>
<html lang="en">
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

 <div class="logout-container">
            <a href="login.php?reset=1" class="btn btn-logout">Cerrar sesión</a>
    </div>
   </header>

<div id="mainContainer">
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

<footer>
  <p>Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
  <a href="mailto:facundovidal492@gmail.com">Email</a></p>
</footer>

</body>
</html>