<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/manual.css">
</head>
<body>
    <header class="header_manual">Como jugar
        <?php if (isset($_GET['back'])): ?>
    <div style="text-align:right; margin-bottom:15px;">
        <a href="<?= htmlspecialchars($_GET['back']) ?>" class="btn btn-manual">Volver</a>
    </div>
<?php endif; ?>
    </header>
    <div class="content">
        <h3 class="title_manual">REQUISITOS:</h3>
        <p class="text_manual">Para jugar, primero debes registrarte o iniciar sesión si ya tienes una cuenta.</p>
        <h3 class="title_manual">LOBBY:</h3>
        <p class="text_manual">Una vez dentro, puedes seleccionar la cantidad de jugadores y el tamaño del tablero.</p>
        <h3 class="title_manual">SORTEO DE TURNOS:</h3>
        <p class="text_manual">Después de eso, se sortearán las posiciones de los jugadores y podrás comenzar a jugar.</p>
        <h3 class="title_manual">JUEGO:</h3>
        <p class="text_manual">El juego consiste en formar la mayor cantidad de plabras ANA posibles.</p>
        <p class="text_manual">Las palabras pueden formarse en vertical, horizntal, o diagonal.</p>
        <p class="text_manual">Cada jugador, puede poner una letra, ya sea la A o la N.</p>
        <p class="text_manual">Si al colocar una letra, no logra formar ninguna palabra ANA, se pasa al siguiente jugador.</p>
        <p class="text_manual">Si al colocar una letra, logra formar una plabra ANA, puede seguir colocando letras.</p>
        <p class="text_manual">Los jugadores iran en el orden que hayan sacado tirando los dados.</p>
        <p class="text_manual">Por cada palabra formada, se le sumara un punto al jugador</p>
        <p class="text_manual">Gana quien tenga mas puntos, y en caso de empate, quienes hayan empatado suman una victoria si es que tienen la mayoria de puntos.</p>
        <p class="text_manual">Tambien podes ganar si el resto de jugadores abandonan la partida, y automaticamente se te suma una victoria.</p>
        <p class="text_manual">Recuerda que puedes cambiar la letra seleccionada durante el juego.</p>
        <h3 class="title_manual">FIN DE JUEGO:</h3>
        <p class="text_manual">EL juego termina cuando no hayan mas espacios disponibles en el tablero, cuando todos hayan abandonado, cuando solo quede un jugador.</p>
        <h3 class="title_manual">¡Diviértete jugando!</h3>

    </div>
    <footer class="footer_manual">
         <p>Creado por Facundo Vidal · <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
  <a href="mailto:facundovidal492@gmail.com">Email</a></p>
    </footer>
</body>
</html>