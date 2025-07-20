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
        <a href="<?= htmlspecialchars($_GET['back']) ?>" class="btn btn-playAgain">Jugar de nuevo</a>
    </div>
<?php endif; ?>
    </header>
    <div id="content">
            
    </div>
    <footer class="footer_ranking">
         <p>Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
  <a href="mailto:facundovidal492@gmail.com">Email</a></p>
    </footer>
</body>
</html>