<?php
class User {
    private $id;
    private $username;
    private $gameWins;
    private $turn; //para manejar el turno
    public function __construct(int $id, string $username, int $gameWins){
        $this->id = $id;
        $this->username = $username;
        $this->gameWins = $gameWins;
    }

    public function getId()  {
        return $this->id;
    }
    public function getUsername()  {
        return $this->username;
    }
    public function getGameWins()  {
        return $this->gameWins;
    }
    public function getShift(){
        return $this->turn;
    }
    public function setShift(){
        return $this->turn;
    }
    public static function registerUser(string $userName, string $password, string $country,string $email, string $birthday){
        $con = new mysqli ("localhost" , "root", "" ,"princess_ana_game") or die ("No se puedo establecer la conexion con el servidor.");
        //preparo la consulta para evitar inyecciones
        $prepareQuery = $con->prepare(
        "INSERT INTO users
            (user_name, password, country, email, birthday)
         VALUES
            (?, ?, ?, ?, ?)"
        );
        if (! $prepareQuery) {
        throw new RuntimeException(
            "Error al preparar la consulta: " . $con->error
        );
        }
        //hago un hash de la contrasenia para guardarla encriptada
        $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT,
        ['cost' => 12]
        );
        $prepareQuery->bind_param(
            "sssss",
            $userName,
            $passwordHash,
            $country,
            $email,
            $birthday
        );
        $con->query("BEGIN");
        if ( $prepareQuery->execute()) {
                    $con->query("COMMIT");
                     $prepareQuery->close();
                     $con->close();
                     return true;
        }
        else{
                    $con->query("ROLLBACK");
                    $prepareQuery->close();
                     $con->close();
                     return false;
        }
    }
   public static function loginUser(string $userName, string $password){
    $con = new mysqli("localhost", "root", "", "princess_ana_game");
    if ($con->connect_errno) {
        throw new RuntimeException("Error de conexión: " . $con->connect_error);
    }
 //  preparar consulta para evitar inyección
    $prepareQuery = $con->prepare(
        "SELECT  id_user,user_name, password
           FROM users
          WHERE user_name = ?"
    );
   
    $prepareQuery->bind_param("s", $userName);
    $prepareQuery->execute();
    $result = $prepareQuery->get_result();
    if ($row = $result->fetch_object()) {
        $prepareQuery->close();
        $con->close();
        if (password_verify($password, $row->password)) {
            return new User ($row->id_user,$row->user_name,5);
        } else {
            return null;
        }
    }
    $prepareQuery->close();
    $con->close();
    return false;
}
    public static function checkUsernameExist(string $userName){
        $con = new mysqli("localhost", "root", "", "princess_ana_game");
        if ($con->connect_errno) {
            throw new RuntimeException("Error de conexión: " . $con->connect_error);
        }
    //  preparar consulta para evitar inyección
        $prepareQuery = $con->prepare(
            "SELECT user_name
            FROM users
            WHERE user_name = ?"
        );
    
        $prepareQuery->bind_param("s", $userName);
        $prepareQuery->execute();
        $result = $prepareQuery->get_result();
        if ($row = $result->fetch_object()) {
            $prepareQuery->close();
            $con->close();
                return true;
        }
        $prepareQuery->close();
        $con->close();
        return false;
    }
     public static function checkEmailExist(string $email){
        $con = new mysqli("localhost", "root", "", "princess_ana_game");
        if ($con->connect_errno) {
            throw new RuntimeException("Error de conexión: " . $con->connect_error);
        }
    //  preparar consulta para evitar inyección
        $prepareQuery = $con->prepare(
            "SELECT email
            FROM users
            WHERE email = ?"
        );
    
        $prepareQuery->bind_param("s", $email);
        $prepareQuery->execute();
        $result = $prepareQuery->get_result();
        if ($row = $result->fetch_object()) {
            $prepareQuery->close();
            $con->close();
                return true;
        }
        $prepareQuery->close();
        $con->close();
        return false;
    }
    }

?>