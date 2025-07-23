<?php
session_start();
if(!isset($_SESSION['player1'])){
        header("Location: login.php");
        exit;
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/ranking.css">
    <script src="../js/ranking.js" type="module"></script>
</head>
<body>
    <header class="header_ranking">Ranking de mejores jugadores
        <?php if (isset($_GET['back'])): ?>
    <div style="text-align:right; margin-bottom:15px;">
                    <a href="login.php?reset=1" class="btn btn-playAgain">Cerrar sesión</a>
<a href="manual.php?back=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-playAgain">¿Cómo jugar?</a>   

        <a href="game.php?selectPlayers=<?= htmlspecialchars($_GET['selectPlayers']) ?>&selectSize=<?= htmlspecialchars($_GET['selectSize']) ?>" class="btn btn-playAgain">Jugar de nuevo</a>
    </div>
<?php endif; ?>
    </header>
    <div id="content">
            
    </div>
    <footer class="footer_ranking">
         <p>      Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> · facundovidal492@gmail.com
</p>
    </footer>
</body>
</html>