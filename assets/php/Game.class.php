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
    public static function getIDLastWinners($playerIds) {
        $countPlayers = count($playerIds);

        if ($countPlayers === 0) {
            return [];
        }
        //para crear el arreglo que se va a usar en la consulta, contiene
        //los id de los jugadores que participan en la partida
        $placeholders = implode(',', array_fill(0, $countPlayers, '?'));

        $pdo = new PDO('mysql:host=localhost;dbname=princess_ana_game', 'root', '');

        $sql = "
            SELECT gw.id_user
            FROM princess_ana_game.game_winners gw
            WHERE gw.id_game = (
                SELECT g.id_game
                FROM princess_ana_game.games g
                JOIN princess_ana_game.user_game ug ON g.id_game = ug.id_game
                WHERE ug.id_user IN ($placeholders)
                GROUP BY g.id_game
                HAVING COUNT(*) = ?
                AND COUNT(*) = (
                    SELECT COUNT(*) 
                    FROM princess_ana_game.user_game 
                    WHERE id_game = g.id_game
                )
                ORDER BY g.date_start DESC
                LIMIT 1
            )
        ";

        $stmt = $pdo->prepare($sql);
        //le agrego la cantidad dejugadores para la consukta
        $params = array_merge($playerIds, [$countPlayers]);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $IDwinners = [];
        foreach ($result as $row) {
            $IDwinners[] = $row['id_user'];
        }
        return $IDwinners;
    }
}

?>