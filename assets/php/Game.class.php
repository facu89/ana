<?php 

class Game {
   
 public static function createGame($players, $winners, $date,$size) {
        $con = new mysqli("localhost", "root", "", "princess_ana_game");

        if ($con->connect_error) {
            die("Conexión fallida: " . $con->connect_error);
        }

        // unjuego nuevo
        $stmt = $con->prepare("INSERT INTO games (date_start,size) VALUES (?,?)");
        $stmt->bind_param("si", $date,$size);

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
        $placeholders = implode(',', array_fill(0, $countPlayers, '?'));

        $pdo = new PDO('mysql:host=localhost;dbname=princess_ana_game', 'root', '');

        $sql = "
            SELECT gw.id_user, g.date_start
            FROM princess_ana_game.game_winners gw
            JOIN princess_ana_game.games g ON gw.id_game = g.id_game
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
        $params = array_merge($playerIds, [$countPlayers]);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $IDwinners = [];
        $date = null;
        foreach ($result as $row) {
            $IDwinners[] = $row['id_user'];
            $date = $row['date_start'];
        }
        $data = new stdClass();
        $data->date = $date;
        $data->IDwinners = $IDwinners;
        return $data;
    }
    public static function getRanking ($size){
        $con = new mysqli("localhost", "root", "", "princess_ana_game");
        if ($con->connect_errno) {
            throw new RuntimeException("Error de conexión: " . $con->connect_error);
        }
        if ($con->connect_error) {
            die("Conexión fallida: " . $con->connect_error);
        }
        $query = "SELECT u.user_name, u.id_user, COUNT(gw.id_user) AS games_won
                FROM princess_ana_game.users u
                LEFT JOIN (
                    SELECT gw.id_user, gw.id_game
                    FROM princess_ana_game.game_winners gw
                    JOIN princess_ana_game.games g ON gw.id_game = g.id_game
                    WHERE g.size = ?
                ) AS gw ON u.id_user = gw.id_user
                GROUP BY u.id_user
                ORDER BY games_won DESC;
";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $size);
        $stmt->execute();
        $resu = $stmt->get_result();
        $ranking = [];
        if($resu->num_rows > 0){
            while($register = $resu->fetch_object()){
                $ranking[] = [
                    'username' => $register->user_name,
                    'id_user' => $register->id_user,
                    'games_won' => (int)$register->games_won
                ];
            }
            $stmt->close();
            $con->close();
            return $ranking;
        } else {
            $stmt->close();
            $con->close();
            return null;
        }
    }
}

?>