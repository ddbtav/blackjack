<?php
//Lots of functions to create users, validate login and update database.
class DB_connection {

    static function printout_users_data()
    {
        $pdo = SELF::createPdo();
        $stmt = $pdo->query('SELECT * FROM users');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
//             echo $row['id'] . ' : ';
//             echo $row['username'] . ' : ';
//             echo $row['password'] . '<br>';
            $_SESSION["id"] = $row["id"];
            
        }
        
        $pdo = null;
    }
    
    
    
    static function checkLoginCredentials($username, $password)
    {
        $pdo = SELF::createPdo();
        $stmt = $pdo->query('SELECT * FROM users');
        $loginValid = false;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usernameDb = $row['username'];
            $passwordDb = $row['password'];
            $idDb = $row['id'];
            if (strtoupper($username) === $usernameDb && $password === $passwordDb) {
                $_SESSION["id"] = $idDb;
                SELF::updateSessions($_SESSION["id"]);
                $loginValid = true;
                break;
            }
        }
        $pdo = null;
        return $loginValid;
    }
    

    
    
    static function username_exists($username)
    {
        $pdo = SELF::createPdo();
        $stmt = $pdo->query('SELECT * FROM users');
        $username_exists = false;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            // echo $row['username'] . '<br>';
            // echo $row['password'] . '<br>';
            
            $usernameDb = $row['username'];
            
            if (strtoupper($username) === $usernameDb) {
                $username_exists = true;
                break;
            }
        }
        
        $pdo = null;
        return $username_exists;
    }
    
    static function createPdo()
    {
        $host = 'localhost'; // 81 at TAV !!!
        $user = 'root';
        $password = '';
        $dbname = 'blackjack';
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    static function insertIntoTable($username,$password){
        $pdo = SELF::createPdo();
        $sql = "INSERT INTO users(username,password) VALUES(:username, :password)";
        $stmt = $pdo->prepare($sql);
        $success=$stmt->execute(['username' => strtoupper($username), 'password' => $password]);
        
       

        // DB: TESTING stats table insert
//         $pdo = SELF::createPdo();
//         $stmtForID = $pdo->query("SELECT * FROM USERS");
//         while($row = $stmtForID->fetch(PDO::FETCH_OBJ)) {
//             if(strtoupper($username == $row->username)) {
//                 $fk_id = $row->id;
//                 $_SESSION["currentUsername"] = $row->username;
//                 $_SESSION["fk_id"] = $fk_id;
//             }
//         }
        SELF::printout_users_data();
            
        $sql = "INSERT INTO stats(id) VALUES(:id)";
        $stmt = $pdo->prepare($sql);
        $id = $stmt->execute(['id' => $_SESSION['id']]);
        
       
        $pdo=null;
        
       
        
        return $success;
    }
    
    
    static function insertIntoTablewrong2($username,$password){
        //         $pdo = SELF::createPdo();
        //         $sql = "INSERT INTO users(id,username,password) VALUES(:id, :username, :password)";
        //         $stmt = $pdo->prepare($sql);
        //         $success=$stmt->execute(['id' => NULL, 'username' => strtoupper($username), 'password' => $password]);
        //         $pdo=null;
        
        // DB: TESTING stats table insert
        $pdo = SELF::createPdo();
        $fk_id = 13;
        $sql = "INSERT INTO stats(id) VALUES(:id)";
        $stmt = $pdo->prepare($sql);
        $success=$stmt->execute(['id' => $fk_id]);
        $pdo=null;
        
        
        return $success;
    }
    
    
    static function insertIntoTable_stats_insert_works_whenYouKnowThevalidID($username,$password){
//         $pdo = SELF::createPdo();
//         $sql = "INSERT INTO users(id,username,password) VALUES(:id, :username, :password)";
//         $stmt = $pdo->prepare($sql);
//         $success=$stmt->execute(['id' => NULL, 'username' => strtoupper($username), 'password' => $password]);
//         $pdo=null;

        // DB: TESTING stats table insert 
        $pdo = SELF::createPdo();
        $sql = "INSERT INTO stats(id) VALUES(:id)";
        $stmt = $pdo->prepare($sql);
        $success=$stmt->execute(['id' => 13]);
        $pdo=null;
        
        
        return $success;
    }
    
    
    
    static function insertIntoTable_working($username,$password){
        $pdo = SELF::createPdo();
        $sql = "INSERT INTO users(id,username,password) VALUES(:id, :username, :password)";
        $stmt = $pdo->prepare($sql);
        $success=$stmt->execute(['id' => NULL, 'username' => strtoupper($username), 'password' => $password]);
        $pdo=null;
        return $success;
    }
    
    static function addMoney($id, $funds) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET balance = (balance + $funds) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
    }
    
    static function deductMoney($id, $funds) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET balance = (balance - $funds) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;

    }
    
    static function updateMoneyWon($id, $funds) {
//        SELF::addMoney($id, $funds);
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET money_won = (money_won + $funds) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in play.php - payout()
    }
    
    static function updateMoneyLost($id, $funds) {
//        SELF::deductMoney($id, $funds);
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET money_lost = (money_lost + $funds) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented play.php - dealers_turn()
        
    }
    
    static function updateHighestBalance($id, $funds) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET highest_balance = $funds WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
    }
    
    static function updateSessions($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET sessions = (sessions + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in checkLoginCredentials()
    }
    
    static function updateHands($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET hands = (hands + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in game.php - setBets()
    }
    
    static function updateWins($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET wins = (wins + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in game.php - payout()
    }
    
    
    static function updateBlackjacks($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET blackjacks = (blackjacks + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //implemented in game.php - check_for_bj 
    }
    
    static function updateLosses($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET losses = (losses + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in game.php - dealers_turn() and checkHand()
    }
    
    static function updateBusts($id) {
        SELF::updateLosses($id);
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET busts = (busts + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in game.php - checkHand()
    }
    
    static function updateTies($id) {
        $pdo = SELF::createPdo();
        $sql = "UPDATE stats SET ties = (ties + 1) WHERE id = $id";
        $success = $pdo->query($sql);
        return true;
        //Implemented in game.php - checkTheWinner()
    }
    
}
