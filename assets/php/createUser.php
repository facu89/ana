<?php


require_once("User.class.php");
$errors    = [];
$createdMsg = '';
if (isset($_POST['username']) &&  isset($_POST['email']) && isset($_POST['birthdate']) 
&& isset($_POST['country']) && (isset($_POST['password']) && isset($_POST['passwordHashed'])) && isset($_POST['password_2'])) {
    $user      = trim($_POST['username']   ?? '');
    $email     = trim($_POST['email']      ?? '');
    $birthdate = trim($_POST['birthdate']  ?? '');
    $country   = trim($_POST['country']    ?? '');
    $pass1     = $_POST['passwordHashed'] ?? $_POST['password'] ?? '';
    $pass2     = $_POST['password_2'      ?? ''];
    if ($user === '' || ! preg_match('/^[a-zA-Z0-9_]{3,20}$/', $user)) {
        $errors[] = 'Usuario inválido (3–20 car., letras, dígitos o _).';
    }
   
    $day = DateTime::createFromFormat('Y-m-d', $birthdate);
    if (! $day || $day->format('Y-m-d') !== $birthdate) {
        $errors[] = 'Fecha de nacimiento inválida.';
    }
    
    // Validar que el usuario tenga al menos 10 años
    if ($day) {
        $today = new DateTime();
        $age = $today->diff($day)->y;
        if ($age < 10) {
            $errors[] = 'Debes tener al menos 10 años para registrarte.';
        }
    }
    
    $passwordHashed = $_POST['passwordHashed'];
    // Validar la contraseña original (no el hash)
    $originalPass = $_POST['password'] ?? '';
  

    if (strlen($originalPass) < 8 ||
        ! preg_match('/[A-Z]/', $originalPass) ||! preg_match('/\d/', $originalPass)
    ) {
        $errors[] = 'La contraseña debe tener ≥8 car., una mayúscula y un dígito.';
    }
    if ($originalPass !== $pass2) {
        $errors[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errors)) {
        if(!User::checkUsernameExist($user)){
            if(!User::checkEmailExist($email)){
                if(User::registerUser( $user,  $passwordHashed,  $country, $email,  $birthdate)){
                    $createdMsg ='Usuario creado con exito, ya puede iniciar sesion.';
                }
                else{
                 $errors[] = 'Ocurrio un error al crear el usuario'; 

                }
            }
            else{
                 $errors[] = 'Este correo ya se encuentra en uso'; 
            }
        }
        else{
            $errors[] = 'Este usuario ya se encuentra en uso'; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario</title>
    <link rel="stylesheet" href="../css/createUser.css">
  <script src="../js/createUser.js"></script>
</head>
<body>
  <header>
   <p class="text_1"> Crear usuario</p> 
  </header>
<div>
 <form action="createUser.php" method="post">
  <div class="mainDiv">
    <div class="division">
            <div>
              <div class="">Usuario:
                <input type="text" name="username" required
                      value="<?= htmlspecialchars($user ?? '') ?>">
              </div>
            
            </div>
            
            <div>
              <div>Email:
                <input type="email" name="email" required
                      value="<?= htmlspecialchars($email ?? '') ?>">
                      <!--  vuelvo a insertar los datos que habia ingresado el usuario -->
              </div>
            </div>
            <div>
              <div>Fecha de nacimiento:
                <input type="date" name="birthdate" required
                      value="<?= htmlspecialchars($birthdate ?? '') ?>">
              </div>
            </div>
        </div>
      <div>

      </div>
        <div class="division">
            <div>
              <div>País:
                <input type="text" name="country" required
                      value="<?= htmlspecialchars($country ?? '') ?>">
              </div>
            </div>
            <div>
              <div>Contraseña:
                <input type="password" name="password" id="inpPassword" required>
                <input type="hidden" name="passwordHashed">
              </div>
            </div>
            <div>
              <div>Repetir contraseña:
                <input type="password" name="password_2" required>
              </div>
            </div>
        </div>
  </div>
   
    <button type="submit" id="btnStartSession">Crear usuario</button>
  </form>
  <?php
  if (! empty($errors)) {
        foreach ($errors as $e) {
            echo "<p class = 'errorText'>".htmlspecialchars($e)."</p>";
        }
    }
    else if($createdMsg !=''){
            echo "<p class = 'successText'>".htmlspecialchars($createdMsg
             )."</p>";

    }
  ?>
    <a href="login.php" class="goToLogin">Iniciar sesion</a>
</div>
      <footer class="lobbyFooter">
    <p>Creado por Facundo Vidal · 
      <a href="https://github.com/facu89" target="_blank">GitHub</a> ·  
      <div>facundovidal492@gmail.com</div>
    </p>

  </footer>
</body>
</html>