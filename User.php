<?php
//JF: User Class keeps display all statistics and to connect to database via id.
        class User {
        public $id;
        public $username;
        public $password;
        public $stats = array();

        function __construct($username){
        $pdo = DB_connection::createPdo();

        $this->id = $_SESSION["id"];

        }

        function getUsername() {
            return $this->username;
        }

        function getID() {
            return $this->id;
        }


        function getSessionStats() {
            //Create Tables for stats of current session. All variables stored in $_SESSION[], never sent to database.
        }

        function getBalance() {
            $pdo = DB_connection::createPdo();
            $result = $pdo->query("SELECT balance FROM stats WHERE id = $this->id");
            foreach($result as $result) {
                $this->stats["balance"] = $result->balance;
            }
            $balance = $this->stats["balance"];
            return $balance;
        }

       function getMoneyWon() {
            $pdo = DB_connection::createPdo();
            $result = $pdo->query("SELECT money_won FROM stats WHERE id = $this->id");
            foreach($result as $result) {
                $this->stats["money_won"] = $result->money_won;
            }
            $money_won = $this->stats["money_won"];
            return $money_won;
        }

        function getMoneyLost() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT money_lost FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["money_lost"] = $result->money_lost;
        }
        $money_lost = $this->stats["money_lost"];
        return $money_lost;
    }
        function getHighestBalance() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT highest_balance FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["highest_balance"] = $result->highest_balance;
        }
        $highest_balance = $this->stats["highest_balance"];
        return $highest_balance;
    }
        function getSessions() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT sessions FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["sessions"] = $result->sessions;
        }
        $sessions = $this->stats["sessions"];
        return $sessions;
    }
        function getHands() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT hands FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["hands"] = $result->hands;
        }
        $hands = $this->stats["hands"];
        return $hands;
    }
        function getWins() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT wins FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["wins"] = $result->wins;
        }
        $wins = $this->stats["wins"];
        return $wins;
    }
        function getBlackjacks() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT blackjacks FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["blackjacks"] = $result->blackjacks;
        }
        $blackjacks = $this->stats["blackjacks"];
        return $blackjacks;
    }
    
    function getLosses() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT losses FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["losses"] = $result->losses;
        }
        $losses = $this->stats["losses"];
        return $losses;
    }
    
    function getBusts() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT busts FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["busts"] = $result->busts;
        }
        $busts = $this->stats["busts"];
        return $busts;
    }
    
    function getTies() {
        $pdo = DB_connection::createPdo();
        $result = $pdo->query("SELECT ties FROM stats WHERE id = $this->id");
        foreach($result as $result) {
            $this->stats["ties"] = $result->ties;
        }
        $ties = $this->stats["ties"];
        return $ties;
    }
    
    function getUserStats() {
           
            $this->stats["balance"] = $this->getBalance();
            $_SESSION["balance"] = $this->stats["balance"];
            $balance = number_format(round($_SESSION["balance"], 2), 2);
            
            $this->stats["blackjacks"] = $this->getBlackjacks();
            $_SESSION["blackjacks"] = $this->stats["blackjacks"];
            $blackjacks = $_SESSION["blackjacks"];
           
            $this->stats["busts"] = $this->getBusts();
            $_SESSION["busts"] = $this->stats["busts"];
            $busts = $_SESSION["busts"];
           
            $this->stats["hands"] = $this->getHands();
            $_SESSION["hands"] = $this->stats["hands"];
            $hands = $_SESSION["hands"];
           
            $this->stats["highest_balance"] = $this->getHighestBalance();
            $_SESSION["highest_balance"] = $this->stats["highest_balance"];
            $highestBalance = number_format(round($_SESSION["highest_balance"], 2), 2);
           
           
            $this->stats["losses"] = $this->getLosses();
            $_SESSION["losses"] = $this->stats["losses"];
            $losses = $_SESSION["losses"];
           
            $this->stats["money_lost"] = $this->getMoneyLost();
            $_SESSION["money_lost"] = $this->stats["money_lost"];
            $moneyLost = number_format(round($_SESSION["money_lost"], 2), 2);
           
            $this->stats["money_won"] = $this->getMoneyWon();
            $_SESSION["money_won"] = $this->stats["money_won"];
            $moneyWon = number_format(round($_SESSION["money_won"], 2), 2);
           
            $this->stats["sessions"] = $this->getSessions();
            $_SESSION["sessions"] = $this->stats["sessions"];
            $sessions = $_SESSION["sessions"];
        
            $this->stats["ties"] = $this->getTies();
            $_SESSION["ties"] = $this->stats["ties"];
            $ties = $_SESSION["ties"];
           
            $this->stats["wins"] = $this->getWins();
            $_SESSION["wins"] = $this->stats["wins"];
            $wins = $_SESSION["wins"];
           
            if($blackjacks == 0) {
                $blackjacksPercent = "0.00 %";
            }
            else {
            $blackjacksPercent = number_format(round($blackjacks / $hands * 100,  2), 2) . " %";
            }
            
            if($busts == 0) {
                $bustsPercent = "0.00 %";   
            }
            else {
                $bustsPercent = number_format(round($busts / $hands *100, 2), 2) . " %";
            }
        
            if($losses == 0) {
                $lossesPercent = "0.00 %";
            }
            else {
                $lossesPercent = number_format(round($losses / $hands * 100, 2), 2) . " %";
            }
        
            if($ties == 0) {
                $tiesPercent = "0.00 %";
            }
            else {
                $tiesPercent = number_format(round($ties / $hands * 100, 2), 2) . ' %';
            }
        
            if($wins == 0) {
                $winsPercent = "0.00 %";
            }
            else {
                $winsPercent = number_format(round($wins / $hands * 100, 2), 2) . ' %';
            }
        
            $gameplayTableOverall = "<h3 class='text-center'>Your Stats</h3>
                                    <p style='text-transform: uppercase;'><b>Gameplay Stats</b></p>
                                    <table class='table table-striped table-hover table-warning'>
                                                <tr><th scope='col'>Stat</th><th scope='col'>Value</th></tr>
                                                <tr><td>Sessions Played</td><td>$sessions</td></tr>
                                                <tr><td>Hands Played</td><td>$hands</td></tr>
                                                <tr><td>Wins</td><td>$wins</td></tr>
                                                <tr><td>Win Rate</td><td>$winsPercent</td></tr>
                                                <tr><td>Blackjacks</td><td>$blackjacks</td></tr>
                                                <tr><td>Blackjack Rate</td><td>$blackjacksPercent</td></tr>
                                                <tr><td>Total Losses</td><td>$losses</td></tr>
                                                <tr><td>Loss Rate</td><td>$lossesPercent</td></tr>
                                                <tr><td>Busts</td><td>$busts</td></tr>
                                                 <tr><td>Bust Rate</td><td>$bustsPercent
                                                 </td></tr>
                                                 <tr><td>Ties</td><td>$ties</td></tr>
                                                 <tr><td>Tie Rate</td><td>$tiesPercent
                                                 </td></tr>
                                                </table><br>";

            $moneyTableOverall = "<p style='text-transform: uppercase;'><b>Money Stats</b></p><table class='table table-striped table-hover table-primary'>
                                                 <tr><th scope='col'>Stat</th><th scope='col'>Value</th></tr>
                                                <tr><td>Current Balance</td><td>$$balance</td></tr>
                                                <tr><td>Total Money Won</td><td>$$moneyWon</td></tr>
                                                <tr><td>Total Money Lost</td><td>$$moneyLost</td></tr></table>";

            $tables = array($gameplayTableOverall, $moneyTableOverall);
           
            return $tables;
        }
}
?>