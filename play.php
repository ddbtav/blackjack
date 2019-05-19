<?php
//JF: User selects how many hands (upto 3) to play against dealer. Also ensures that bets don't exceed User's account balance.
include_once "bj_package.php";

session_start();
$game = unserialize($_SESSION['game']);

if (isset($_POST['play_again'])) {
    $game->setStatus("just_started");
    $_SESSION['game'] = serialize($game);   
}

if (isset($_POST['hand1_hit'])) {
    // DB: hands may be - 1,2,3
    $game->draw_card(1);
    $game->check_hand(1);
    $_SESSION['game'] = serialize($game);   
}

if (isset($_POST['hand1_double'])) {
    $bet = $game->getBets();
    $fundsRemaining = $_SESSION["userObj"]->getBalance();
    if($bet[1] > $fundsRemaining) {
        echo "Sorry you don't have enough funds to double down.<br>Please add more money.";
    }

    else {
    $game->doubleBet(1);
    $game->draw_card(1);
    $game->check_hand(1);
    if($game->hand_status[1] == "Playing") {
        $game->hand_status[1] = "Standing";
    }
    $_SESSION['game'] = serialize($game);
    }
}

if (isset($_POST["hand1_insurance"])){
    $insuranceBet = $game->bets[1] / 2;
    $currentBalance = $_SESSION["userObj"]->getBalance();
    
    if($insuranceBet > $currentBalance) {
        echo "Sorry, you don't have enough funds to purchase insurance. Please add more money.";
    }
    
    else {
        $game->insuranceBets[1] = $insuranceBet;
        DB_connection::deductMoney($_SESSION["id"], $insuranceBet);
    }
    
    $_SESSION['game'] = serialize($game);
}

if (isset($_POST["hand1_split"])) {
    
    $game->splitHand(1);
    $_SESSION['game'] = serialize($game); 
    
    
}
if (isset($_POST['hand1_stand'])) {
    // DB: hands may be - 1,2,3
    $game->hand_status[1] = "Standing";
    $_SESSION['game'] = serialize($game);   
}

if (isset($_POST['hand2_hit'])) {
    $game->draw_card(2);
    $game->check_hand(2);
    $_SESSION['game'] = serialize($game);
}

if (isset($_POST['hand2_double'])) {
    $bet = $game->getBets();
    $fundsRemaining = $_SESSION["userObj"]->getBalance();
    if($bet[2] > $fundsRemaining) {
        echo "Sorry you don't have enough funds to double down.<br>Please add more money.";
    }
    else {
    $game->doubleBet(2);
    $game->draw_card(2);
    $game->check_hand(2);
    if($game->hand_status[2] == "Playing") {
        $game->hand_status[2] = "Standing";
    }
    $_SESSION['game'] = serialize($game);
    }
}

if (isset($_POST["hand2_insurance"])){
    $insuranceBet = $game->bets[2] / 2;
    $currentBalance = $_SESSION["userObj"]->getBalance();
    
    if($insuranceBet > $currentBalance) {
        echo "Sorry, you don't have enough funds to purchase insurance. Please add more money.";
    }
    
    else {
        $game->insuranceBets[2] = $insuranceBet;
        DB_connection::deductMoney($_SESSION["id"], $insuranceBet);
    }
    $_SESSION['game'] = serialize($game);
}
if (isset($_POST["hand2_split"])) {
    
    $game->splitHand(2);
    $_SESSION['game'] = serialize($game);  
}
if (isset($_POST['hand2_stand'])) {
    $game->hand_status[2] = "Standing";
    $_SESSION['game'] = serialize($game);
}

if (isset($_POST['hand3_hit'])) {
    $game->draw_card(3);
    $game->check_hand(3);
    $_SESSION['game'] = serialize($game);
}

if (isset($_POST['hand3_double'])) {
    $bet = $game->getBets();
    $fundsRemaining = $_SESSION["userObj"]->getBalance();
    if($bet[3] > $fundsRemaining) {
        echo "Sorry you don't have enough funds to double down.<br>Please add more money.";
    }
    else {
    $game->doubleBet(3);
    $game->draw_card(3);
    $game->check_hand(3);
    if($game->hand_status[3] == "Playing") {
        $game->hand_status[3] = "Standing";
    }
    $_SESSION['game'] = serialize($game);
    }
}

if (isset($_POST["hand3_insurance"])){
    $insuranceBet = $game->bets[3] / 2;
    $currentBalance = $_SESSION["userObj"]->getBalance();
    
    if($insuranceBet > $currentBalance) {
        echo "Sorry, you don't have enough funds to purchase insurance. Please add more money.";
    }
    
    else {
        $game->insuranceBets[3] = $insuranceBet;
        DB_connection::deductMoney($_SESSION["id"], $insuranceBet);
    }
    
    $_SESSION['game'] = serialize($game);
}
if (isset($_POST["hand3_split"])) {
    
    $game->splitHand(3);
    $_SESSION['game'] = serialize($game);  
}
if (isset($_POST['hand3_stand'])) {
    $game->hand_status[3] = "Standing";
    $_SESSION['game'] = serialize($game);
}

if(count($game->players) > 4) {
    $splits = count($game->players) - 4; 
        for($i = 4; $i < count($game->players); $i++) {
            $hit = "hand". $i . "_hit";
            if (isset($_POST[$hit])) {
                $game->draw_card($i);
                $game->check_hand($i);
                $_SESSION['game'] = serialize($game);
            }
            $double = "hand" . $i . "_double";
            if (isset($_POST[$double])) {
                $bet = $game->getBets();
                $fundsRemaining = $_SESSION["userObj"]->getBalance();
                if($bet[$i] > $fundsRemaining) {
                    echo "Sorry you don't have enough funds to double down.<br>Please add more money.";
                }
                else {
                $game->doubleBet($i);
                $game->draw_card($i);
                $game->check_hand($i);
                if($game->hand_status[$i] == "Playing") {
                    $game->hand_status[$i] = "Standing";
                }
                $_SESSION['game'] = serialize($game);
                }
            }
            $insurance = "hand" . $i . "_insurance";
            if (isset($_POST[$insurance])){
                $insuranceBet = $game->bets[$i] / 2;
                $currentBalance = $_SESSION["userObj"]->getBalance();

                if($insuranceBet > $currentBalance) {
                    echo "Sorry, you don't have enough funds to purchase insurance. Please add more money.";
                }

                else {
                    $game->insuranceBets[$i] = $insuranceBet;
                    DB_connection::deductMoney($_SESSION["id"], $insuranceBet);
                }

                $_SESSION['game'] = serialize($game);
            }
            $split = "hand" . $i . "_split";
            if (isset($_POST[$split])) {

                $game->splitHand($i);
                $_SESSION['game'] = serialize($game);  
            }
            $stand = "hand" . $i . "_stand";
            if (isset($_POST[$stand])) {
                $game->hand_status[$i] = "Standing";
                $_SESSION['game'] = serialize($game);
            }
        }
    
}


if (isset($_POST['submit_bets'])) {
    if ($_POST['bet1'] == 0 && $_POST['bet2'] == 0 && $_POST['bet3'] == 0) {
        echo "You must place at least one bet to play!";
    }
    else if (($_POST['bet1'] + $_POST['bet2'] + $_POST['bet3']) > $_SESSION['balance']) {
        echo "Your total bet may not exceed your balance!";
    }
    else {
        $game->setStatus("bets_placed");
        $game->setBets($_POST['bet1'], $_POST['bet2'], $_POST['bet3']);
        DB_connection::deductMoney($_SESSION["userObj"]->id, $_POST['bet1']);
        DB_connection::deductMoney($_SESSION["userObj"]->id, $_POST['bet2']);
        DB_connection::deductMoney($_SESSION["userObj"]->id, $_POST['bet3']);
        $game->startGame();
        $game->check_for_BJ();
        
        $_SESSION['game'] = serialize($game);   /////// serialize later - after all actions
    }
}



Htmller::print_html_header();

//Htmller::print_play_header();

if ($game->getStatus() == "just_started") {
    //DB: bets table

Htmller::print_play_header();

    echo <<<E
<div class="container d-flex justify-content-center mt-5">
    <form method='post' action=''>
        <div class="row">
            <div class="card-deck">
                <div class="card border-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header text-center">Hand 1</div>
                    <div class="card-body text-secondary">
                        <h5 class="card-title text-center">Make your bet</h5>
                        <p class="card-text"><input style="width: 100px;" class="form-control" type='number' id="bet1" name='bet1' min="0" max="1000" step="1" value="0"></p>
                    </div>
                </div>
                <div class="card border-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header text-center">Hand 2</div>
                    <div class="card-body text-secondary">
                        <h5 class="card-title text-center">Make your bet</h5>
                        <p class="card-text"><input style="width: 100px;" class="form-control" type='number' id="bet2" name='bet2' min="0" max="1000" step="1" value="0"></p>
                    </div>
                </div>
                <div class="card border-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header text-center">Hand 3</div>
                    <div class="card-body text-secondary">
                        <h5 class="card-title text-center">Make your bet</h5>
                        <p class="card-text"><input style="width: 100px;" class="form-control" type='number' id="bet3" name='bet3' min="0" max="1000" step="1" value="0"></p>
                    </div>
                </div>
            </div>
        </div>
    <div class="text-center">
    <button type="submit" name="submit_bets" class="btn btn-primary">Deal</button>
    </div>
</form>
</div>
    
E;
 
}
 

if ($game->getStatus() == "bets_placed") {
    if ($game->all_hands_finished()){
        //echo "TODO: All hands finished - call dealer for action!!!";
        $game->setStatus("dealers_turn");
    }
}


if ($game->getStatus() == "dealers_turn") {
    $totalInsurance = 0;
    for($i = 1; $i < count($game->players); $i++) {
        $totalInsurance += $game->insuranceBets[$i];
    }
    if($totalInsurance > 0){
        if($game->players[0]->hand[0]->value == 10){
            
            echo "<br><h3 style='text-align: center;'>The dealer's hidden card is a " . $game->players[0]->hand[0]->face . "<br>You win your insurance bet of <b>$$totalInsurance</b>!</h3><br>";
            DB_connection::addMoney($_SESSION["id"], $totalInsurance);
        }
        else {
            echo "<br><h3 style='text-align: center;'>The dealer's hidden card was a " . $game->players[0]->hand[0]->face . "<br>You lost your insurance bet of <b>$$totalInsurance</b>!</h3><br>";
        }
    }
    if ($game->no_one_standing()){
        $game->draw_table();
        $game->draw_splitted_hands();
        Htmller::print_play_again_button();
        
    } else {
    // echo "TODO: dealer's action ...";
    //$game->dealers_turn();
        $game->dealers_turn();
        $game->payout();
        $game->draw_finished_table();
        $game->draw_splitted_hands();

        Htmller::print_play_again_button();
    }
}



if ($game->getStatus() == "bets_placed") {
    $game->draw_table();
    $game->draw_splitted_hands();
}

if ($game->getStatus() == "bets_placed") {
   if($_SESSION["cheat"] == "on") {
    echo "<hr>";
    echo $game->deck->cheat_cards_counting();
    echo "<hr>";
   }
}

echo "<hr>";
$tables = $_SESSION["userObj"]->getUserStats();
echo "$tables[0] <br><br>$tables[1]";
Htmller::print_html_footer();
?>