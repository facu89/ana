<?php 

class Game {
    private $players;
    private $size;
    private $status;
    public function __construct( $players, $size, $status) {
        $this->players = $players;
        $this->size = $size;
        $this->status = $status;        
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getSize() {
        return $this->size;
    }
 public static function createGame($players, $winners, $date) {
    $con = new mysqli("localhost", "root", "", "princess_ana_game");

    if ($con->connect_error) {
        die("Conexión fallida: " . $con->connect_error);
    }

    // unjuego nuevo
    $stmt = $con->prepare("INSERT INTO games (date_start) VALUES (?)");
    $stmt->bind_param("s", $date);

    if (!$stmt->execute()) {
        die("Error al insertar juego: " . $stmt->error);
    }

    $id_game = $stmt->insert_id;
    $stmt->close();

    //insertar jugadores en user_game
    $stmt = $con->prepare("INSERT INTO user_game (id_game, id_user) VALUES (?, ?)");
    for ($i = 0; $i < count($players); $i++) {
        $id_user = $players[$i];
        $stmt->bind_param("ii", $id_game, $id_user);

        if (!$stmt->execute()) {
            echo "Error al insertar jugador con ID $id_user: " . $stmt->error;
        }
    }
    $stmt->close();

    // ahora ingreso los ganadores
        $stmt = $con->prepare("INSERT INTO game_winners (id_game, id_user) VALUES (?, ?)");
        for ($i = 0; $i < count($winners); $i++) {
            $id_user = $winners[$i];
            $stmt->bind_param("ii", $id_game, $id_user);
            if (!$stmt->execute()) {
                echo "Error al insertar ganador con ID $id_user: " . $stmt->error;
            }
        }
        $stmt->close();
    

    $con->close();
    return $id_game;
}
};
?>