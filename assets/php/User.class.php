<?php
class User {
    private $id;
    private $username;
    private $gameWins;
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
        // Guardar el hash recibido del cliente directamente
        $prepareQuery->bind_param(
            "sssss",
            $userName,
            $password,
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
   public static function loginUser(string $userName, string $passwordHashed){
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
        // Comparar el hash recibido del cliente directamente con el guardado
        if ($passwordHashed === $row->password) {
            return new User ($row->id_user,$row->user_name,self::getGameWinsUser($row->id_user));
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
     public static function getGameWinsUser($id){
    $con = new mysqli("localhost", "root", "", "princess_ana_game");
    if ($con->connect_errno) {
        throw new RuntimeException("Error de conexión: " . $con->connect_error);
    }
    $prepareQuery = $con->prepare(
        "SELECT COUNT(*) AS partidas_ganadas
         FROM game_winners
         WHERE id_user = ?"
    );
    $prepareQuery->bind_param("i", $id);
    $prepareQuery->execute();
    $result = $prepareQuery->get_result();
    if ($row = $result->fetch_object()) {
        $prepareQuery->close();
        $con->close();
        return $row->partidas_ganadas;
    }
    $prepareQuery->close();
    $con->close();
    return 0;
        }

    public static function getGamesWonByUser($id) {
        $con = new mysqli("localhost", "root", "", "princess_ana_game");
        if ($con->connect_errno) {
            throw new RuntimeException("Error de conexión: " . $con->connect_error);
        }
        $prepareQuery = $con->prepare(
            "SELECT COUNT(*) AS partidas_ganadas
             FROM game_winners
             WHERE id_user = ?"
        );
        $prepareQuery->bind_param("i", $id);
        $prepareQuery->execute();
        $result = $prepareQuery->get_result();
        if ($row = $result->fetch_object()) {
            $prepareQuery->close();
            $con->close();
            return $row->partidas_ganadas;
        }
        $prepareQuery->close();
        $con->close();
        //por las dudas
        return 0;
    }
} ?>