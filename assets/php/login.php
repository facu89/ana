<?php
session_start();
session_unset();
session_destroy();
session_start();
require_once("User.class.php");


$countPlayers = (int)($_POST['selectPlayers'] ?? $_GET['selectPlayers'] ?? 3);
$size         = (int)($_POST['selectSize']    ?? $_GET['selectSize']    ?? 3);
$loginPlayer  = (int)($_POST['loginPlayer']   ?? $_GET['loginPlayer']   ?? 1);

$errors = [];

// Protección extra: si el primer usuario ya está logueado y se accede a login.php sin POST, redirigir según el jugador
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['player1'])) {
    // Si es el primer jugador, lo mandamos al lobby
    if ($loginPlayer === 1) {
        header("Location: lobby.php");
        exit;
    }
    // Si es el resto, lo mandamos a game.php si ya están todos
    if ($loginPlayer > 1 && isset($_SESSION['player' . $countPlayers])) {
        header("Location: game.php?selectPlayers=$countPlayers&selectSize=$size");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $user = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!User::checkUsernameExist($user)) {
    $errors[] = "Nombre de usuario inválido";
  } elseif (!$user = User::loginUser($user, $password)) {
    $errors[] = "Usuario o contraseña incorrectos";
  } else {
    if (!isset($_SESSION['player1'])) { 
      $_SESSION['player1'] = $user;
      header("Location: lobby.php");
      exit;
    }
    $_SESSION['player' . $loginPlayer] = $user;
    if ($loginPlayer < $countPlayers) {
      $nextPlayer = $loginPlayer + 1;
      header("Location: login.php?selectPlayers=$countPlayers&selectSize=$size&loginPlayer=$nextPlayer");
      exit;
    }
    header("Location: game.php?selectPlayers=$countPlayers&selectSize=$size");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ana – Login Jugador <?= $loginPlayer ?></title>
  <link rel="stylesheet" href="../css/login.css">
<!--  <script src="login.js" type="text/javascript"></script> -->
</head>
<body>
  <header><p class="WelcomeText_1">Bienvenido a Ana</p></header>
  <p class="WelcomeText_2">
    <?php if (!isset($_GET['selectPlayers'])): ?>
    <p class="WelcomeText_2">Por favor, inicie sesión para comenzar.</p>
    <?php else: ?>
    <p class="WelcomeText_2">
        Ingrese credenciales del jugador 
        <?= htmlspecialchars($loginPlayer) ?> de 
        <?= htmlspecialchars($countPlayers) ?>.
    </p>
    <?php endif; ?>

  <form action="login.php" method="post">
    <input type="hidden" name="selectPlayers" value="<?= $countPlayers ?>">
    <input type="hidden" name="selectSize"    value="<?= $size ?>">
    <input type="hidden" name="loginPlayer"   value="<?= $loginPlayer ?>">
    <input type="hidden" name="passwordHashed">

    <label>Usuario:
      <input type="text" name="username"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
             required>
    </label>

    <label>Contraseña:
      <input type="password" name="password" id="inpPassword" required>
    </label>

    <button type="submit" class="btn" id="btnStartSession">Iniciar sesión</button>
  </form>

  <?php foreach ($errors as $e): ?>
    <p class="errorText"><?= htmlspecialchars($e) ?></p>
  <?php endforeach; ?>

  <div>
    <p class="adivsorText">¿No tenés una cuenta?</p>
    <a href="createUser.php">Crear una cuenta</a>
  </div>

  <footer class="lobbyFooter">
    <p>
      Creado por Facundo Vidal ·
      <a href="https://github.com/facu89" target="_blank">GitHub</a> ·
      facundovidal492@gmail.com
    </p>
  </footer>
</body>
</html>