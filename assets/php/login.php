<?php
require_once __DIR__ . '/User.class.php';
if (isset($_GET['reset'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    session_start();
}


$countPlayers = (int)($_POST['selectPlayers'] ?? $_GET['selectPlayers'] ?? 3);
$size         = (int)($_POST['selectSize']    ?? $_GET['selectSize']    ?? 3);
$loginPlayer  = (int)($_POST['loginPlayer']   ?? $_GET['loginPlayer']   ?? 1);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)) {
    $loginPlayer = (int)($_POST['loginPlayer'] ?? $loginPlayer);
    $countPlayers = (int)($_POST['selectPlayers'] ?? $countPlayers);
    $size = (int)($_POST['selectSize'] ?? $size);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['player1'])) {
    if ($loginPlayer === 1) {
        header("Location: lobby.php");
        exit;
    }
    if ($loginPlayer > 1 && isset($_SESSION['player' . $countPlayers])) {
        header("Location: game.php?selectPlayers=$countPlayers&selectSize=$size");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $user = trim($_POST['username'] ?? '');
  // Usar el hash si está presente
  $password = $_POST['passwordHashed'] ?? $_POST['password'] ?? '';
  if (!User::checkUsernameExist($user)) {
    $errors[] = "Nombre de usuario inválido";
  } 
  if (!$user = User::loginUser($user, $password)) {
    $errors[] = "Usuario o contraseña incorrectos";
  } else {
    if (!isset($_SESSION['player1'])) { 
        $_SESSION['player1'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'gameWins' => $user->getGameWins()
        ];
        header("Location: lobby.php");
        exit;
    }
    $isRegistered = false;
    for ($i = 1; $i < $loginPlayer; $i++) {
        if ($_SESSION['player' . $i]['username'] == $user->getUsername()) {
            $errors[] = "Este jugador ya se encuentra registrado para esta partida. Ingrese otro usuario.";
            $isRegistered = true;
            break;
        }
    }
    if (!$isRegistered) {
        $_SESSION['player' . $loginPlayer] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'gameWins' => $user->getGameWins()
        ];
        if ($loginPlayer < $countPlayers) {
            $nextPlayer = $loginPlayer + 1;
            header("Location: login.php?selectPlayers=$countPlayers&selectSize=$size&loginPlayer=$nextPlayer");
            exit;
        }
        header("Location: game.php?selectPlayers=$countPlayers&selectSize=$size");
        exit;
    }
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
    <?php if ($loginPlayer === 1): ?>
      Por favor, inicie sesión para comenzar.
    <?php else: ?>
      Ingrese credenciales del jugador <?= htmlspecialchars($loginPlayer) ?> de <?= htmlspecialchars($countPlayers) ?>.
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