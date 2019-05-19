<?php
//JF: This class handles all gameplay - betting, drawing cards, determining winner and payout.
class Game
{

    public $status = "";

    public $deck;

    public $players = array();

    public $bets = array();
    
    public $insuranceBets = array();
    
    public $hand_status = array();

    public $cheat;
    
    public function getBets()
    {
        return $this->bets;
    }

    public function setBets($bet1, $bet2, $bet3)
    {
        $this->bets[1] = $bet1;
        $this->bets[2] = $bet2;
        $this->bets[3] = $bet3;
        
        for ($i = 1; $i <= 3; $i++){
            if ($this->bets[$i] == 0) {
                $this->hand_status[$i] = "Not playing";
            } else {
                $this->hand_status[$i] = "Playing";
                DB_connection::updateHands($_SESSION["id"]);
            }
        }
        
        
    }
    
    public function doubleBet($handNumber) {
        $this->bets[$handNumber] *= 2;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function startGame()
    {
        $this->insuranceBets[1] = 0;
        $this->insuranceBets[2] = 0;
        $this->insuranceBets[3] = 0;
        $this->deck = new Decks($_SESSION["decks"]);
        shuffle($this->deck->_cards);

        $this->players[0] = new Computer('House');

        // DB: filling hands that have bets with cards.
        for ($i = 1; $i <= 3; $i ++) {
            $this->players[$i] = new Human($this->bets[$i]);
            if ($this->bets[$i] > 0) {
                $this->players[$i]->addCardToHand(array_shift($this->deck->_cards));
                $this->players[$i]->addCardToHand(array_shift($this->deck->_cards));
            }
        }
        $this->players[0]->addCardToHand(array_shift($this->deck->_cards));
        $this->players[0]->addCardToHand(array_shift($this->deck->_cards));

        $_SESSION['players'] = $this->players;
    }

    
    public function draw_table()
    { 
        Htmller::print_play_header();

        ?>
<div class="container mt-3">
    
	<div class="row  d-flex justify-content-center">
		<div class="card-deck">
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Dealer</div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center"><?php if($_SESSION["cheat"] == "on") {echo "Score: " . $this->players[0]->calculateScoreInHand();} else { echo "Score: ??";} ?> </h5>
					<p class="card-text"> <?php ($_SESSION["cheat"] == "on") ? $this->players[0]->show_all_cards() : $this->players[0]->showHand();?></p>
				</div>
			</div>
		</div>
	</div>

	<div class="row  d-flex justify-content-center">
		<div class="card-deck">
			<div class="card border-secondary mb-3" >
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[1], 2); if($this->insuranceBets[1] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[1], 2);} ?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[1]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[1]>0) {echo "Score: " . $this->players[1]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[1]->showHand(); echo "<br>";?>
                        		
					
					<?php if ($this->hand_status[1] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand1_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand1_stand"
							class="btn btn-outline-primary">Stand</button>
                        <button type="submit" name="hand1_double"
							class="btn btn-outline-primary">Double</button>
                        <?php if($this->insuranceIsPossible() && $this->insuranceBets[1] == 0){ echo '<div class="text-center mt-3"><button type="submit" name="hand1_insurance"
							class="btn btn-outline-primary">Buy Insurance</button></div>';}?>
                        <?php if($this->splitIsPossible(1)){ echo '<div class="text-center mt-3"><button type="submit" name="hand1_split"
							class="btn btn-outline-primary">Split Hand</button></div>';}?>
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[2], 2); if($this->insuranceBets[2] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[2], 2);}?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[2]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[2]>0) {echo "Score: " . $this->players[2]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[2]->showHand(); echo "<br>";?>
                        		
                    
					
					<?php if ($this->hand_status[2] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand2_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand2_stand"
							class="btn btn-outline-primary">Stand</button>
                        <button type="submit" name="hand2_double"
							class="btn btn-outline-primary">Double</button>
                        <?php if($this->insuranceIsPossible() && $this->insuranceBets[2] == 0){ echo '<div class="text-center mt-3"><button type="submit" name="hand2_insurance"
							class="btn btn-outline-primary">Buy Insurance</button></div>';}?>
                        <?php if($this->splitIsPossible(2)){ echo '<div class="text-center mt-3"><button type="submit" name="hand2_split"
							class="btn btn-outline-primary">Split Hand</button></div>';}?>
                        
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[3], 2); if($this->insuranceBets[3] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[3], 2);}?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[3]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[3]>0) {echo "Score: " . $this->players[3]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[3]->showHand(); echo "<br>";?>
                        		
                    
					
					<?php if ($this->hand_status[3] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand3_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand3_stand"
							class="btn btn-outline-primary">Stand</button>
                        <button type="submit" name="hand3_double"
							class="btn btn-outline-primary">Double</button>
                        <?php if($this->insuranceIsPossible() && $this->insuranceBets[3] == 0){ echo '<div class="text-center mt-3"><button type="submit" name="hand3_insurance"
							class="btn btn-outline-primary">Buy Insurance</button></div>';} ?>
                        <?php if($this->splitIsPossible(3)){ echo '<div class="text-center mt-3"><button type="submit" name="hand3_split"
							class="btn btn-outline-primary">Split Hand</button></div>';}?>
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
        
    }
    
        public function draw_finished_table()
    {

        Htmller::print_play_header();
        
        ?>
<div class="container mt-3">

	<div class="row  d-flex justify-content-center">
		<div class="card-deck">
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Dealer</div>
				<div class="card-body text-secondary">
                    <h5 class="card-title text-center"><?php if ($this->players[0]->calculateScoreInHand()>21) {echo "Busted!";} ?></h5>
					<h5 class="card-title text-center"><?php echo "Score: " . $this->players[0]->calculateScoreInHand(); ?></h5>
					<p class="card-text"> <?php $this->players[0]->show_all_cards();?></p>
				</div>
			</div>
		</div>
	</div>

	<div class="row  d-flex justify-content-center">
		<div class="card-deck">
			<div class="card border-secondary mb-3" >
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[1], 2); if($this->insuranceBets[1] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[1], 2);} ?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[1]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[1]>0) {echo "Score: " . $this->players[1]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[1]->showHand(); echo "<br>";?>
                        		
					
					<?php if ($this->hand_status[1] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand1_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand1_stand"
							class="btn btn-outline-primary">Stand</button>
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[2], 2);if($this->insuranceBets[2] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[2], 2);} ?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[2]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[2]>0) {echo "Score: " . $this->players[2]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[2]->showHand(); echo "<br>";?>
                        		
                    
					
					<?php if ($this->hand_status[2] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand2_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand2_stand"
							class="btn btn-outline-primary">Stand</button>
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
			<div class="card border-secondary mb-3">
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[3], 2); if($this->insuranceBets[3] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[3], 2);} ?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[3]; ?></h5>
					<h5 class="card-title text-center"><?php if ($this->bets[3]>0) {echo "Score: " . $this->players[3]->calculateScoreInHand();} ?></h5>
					<p class="card-text text-center">
                        		<?php $this->players[3]->showHand(); echo "<br>";?>
                        		
                    
					
					<?php if ($this->hand_status[3] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand3_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand3_stand"
							class="btn btn-outline-primary">Stand</button>
					</form>
					<?php }?> 
					</p>
				</div>
			</div>
		</div>
	</div>


</div>

<?php
        // E;
    }

    
    public function cheat_cards_counting(){
    // DB: done in deck class
        
        $cheat_line = "CHEAT: Cards left in the deck: ";
        $cards_left = sizeof($this->deck->_cards);
        $cheat_line .= $cards_left . " | ";

        // return "CHEAT: Cards left in the deck: ... | 2-6s: ... | 7-9s: ... | 10-Ks: | As: ... |";
        return $cheat_line;
    }

    
    public function draw_card($hand) {
        $this->players[$hand]->addCardToHand(array_shift($this->deck->_cards));
    }
    
    public function check_hand($hand) {
        $score = $this->players[$hand]->calculateScoreInHand();
        if ($score>21) {
            $this->hand_status[$hand] = "BUSTED!";

            DB_connection::updateBusts($_SESSION["id"]);
                
            
        }
        if ($score == 21) {
            $this->hand_status[$hand] = "Standing";
        }
    }
    
    
    public function check_for_BJ(){
        $total_hands = count($this->players);
        for ($i = 1; $i < $total_hands; $i++){
            if ($this->bets[$i] > 0) {
                if ($this->players[$i]->calculateScoreInHand() == 21) {
                    $this->hand_status[$i] = "BlackJack!";
                    DB_connection::updateBlackjacks($_SESSION["id"]);
                }
            }
        }
    }
    
    
    public function all_hands_finished(){
        $total_hands = count($this->players);
        for ($i = 1; $i < $total_hands; $i++){
            if ($this->bets[$i] > 0) {
                if ($this->hand_status[$i] == "Playing") {
                    return false;
                }
            }
        }
        return true;
    }
    
     public function no_one_standing(){
        $total_hands = count($this->players); 
        for ($i = 1; $i < $total_hands; $i++){
            if ($this->bets[$i] > 0) {
                if ($this->hand_status[$i] == "Standing" || $this->hand_status[$i] == "BlackJack!") {
                    
                   // echo "someone is in game!!!";
                    return false;
                }
            }
        }
       // echo "everybody is out!!!";
        return true;
    }
    
    public function insuranceIsPossible() {
        if($this->players[0]->hand[1]->face == "A") {
            return true;
        }
        
        else {
            return false;
        }
    }
    
    public function splitIsPossible($player) {
        $balance = $_SESSION["userObj"]->getBalance();
        if($this->players[$player]->hand[0]->value == $this->players[$player]->hand[1]->value && $balance >= $this->bets[$player]) {
            return true;
        }
        
        else {
            return false;
        }
    }
    
    public function splitHand($player) {
        $newPlayer = new Human("Splitted");
        array_push($this->players, $newPlayer); 
        $index = count($this->players) - 1;
        $this->bets[$index] = $this->bets[$player];
        DB_connection::updateHands($_SESSION["id"]);
        $this->hand_status[$index] = "Playing";
        $this->insuranceBets[$index] = 0;
        DB_connection::deductMoney($_SESSION["id"], $this->bets[$index]);
        $firstCard = array_pop($this->players[$player]->hand);
        array_push($this->players[$index]->hand, $firstCard);
        $this->players[$index]->addCardToHand(array_shift($this->deck->_cards));
        $this->players[$player]->addCardToHand(array_shift($this->deck->_cards));
         $_SESSION['game'] = serialize($this);
            
    }
    
    public function draw_splitted_hands() {
        if(count($this->players) == 4) {
            echo "";
        }
        
        else {
            echo '<div class="row  d-flex justify-content-center">';
            
            for($i = 4; $i < count($this->players); $i++) { ?>
            

		<div class="card-deck">
			<div class="card border-secondary mb-3" >
				<div class="card-header text-center">Bet: <?php echo "$" . number_format($this->bets[$i], 2); if($this->insuranceBets[$i] > 0) { echo "<br>Insurance: $" . number_format($this->insuranceBets[$i], 2);}?></div>
				<div class="card-body text-secondary">
					<h5 class="card-title text-center">Hand status: <?php echo $this->hand_status[$i]; ?></h5>
					<h5 class="card-title text-center">Score: <?php echo $this->players[$i]->calculateScoreInHand(); ?></h5>
					<p class="card-text text-center"><?php $this->players[$i]->showHand(); echo "<br>";?>
                        		
                        		
					
					<?php if ($this->hand_status[$i] == "Playing") {?> 
					<form method="post" action="">
						<button type="submit" name="hand<?php echo $i;?>_hit"
							class="btn btn-outline-primary">Hit</button>
						<button type="submit" name="hand<?php echo $i;?>_stand"
							class="btn btn-outline-primary">Stand</button>
                        <button type="submit" name="hand<?php echo $i;?>_double"
							class="btn btn-outline-primary">Double</button>
                        <?php if($this->insuranceIsPossible() && $this->insuranceBets[$i] == 0){?> 
                        <div class='text-center mt-3'>
                            <button type='submit' name="hand<?php echo $i;?>_insurance"
							class='btn btn-outline-primary'>Buy Insurance</button></div><?php } ?>
                        <?php if($this->splitIsPossible($i)){?>
                        <div class='text-center mt-3'><button type='submit' name="hand<?php echo $i;?>_split"
							class="btn btn-outline-primary">Split Hand</button></div><?php }?>
					</form>
					<?php }?>  
					</p>
				</div>
			</div>
		</div>
        <?php             }
	       echo '</div>';
        }
    }

    public function dealers_turn(){
        while ($this->players[0]->calculateScoreInHand()<17){
            $this->players[0]->addCardToHand(array_shift($this->deck->_cards));
        }
        
        for ($i = 1; $i < count($this->players); $i++){
            if ($this->bets[$i] > 0) {
                if ($this->hand_status[$i] == "Standing" ){
                    if($this->players[0]->calculateScoreInHand() > 21 ){
                        $this->hand_status[$i] = "Win!";
                    } else {
                        if($this->players[$i]->calculateScoreInHand() > $this->players[0]->calculateScoreInHand() ){
                            $this->hand_status[$i] = "Win!";
                            
                        } else if ($this->players[$i]->calculateScoreInHand() == $this->players[0]->calculateScoreInHand()) {
                            $this->hand_status[$i] = "Tie!";
                            DB_connection::updateTies($_SESSION["id"]);
                        } else {
                            $this->hand_status[$i] = "Lost";
                            DB_connection::updateLosses($_SESSION["id"]);
                            DB_connection::updateMoneyLost($_SESSION["id"], $this->bets[$i]);
                        }
                    }
                }
                if($this->hand_status[$i] == "BUSTED!") {
                    DB_connection::updateMoneyLost($_SESSION["id"], $this->bets[$i]);
                }
                if ($this->hand_status[$i] == "BlackJack!") {
                        if ($this->players[$i]->calculateScoreInHand() == $this->players[0]->calculateScoreInHand()) {
                            $this->hand_status[$i] = "Tie!";
                            DB_connection::updateTies($_SESSION["id"]);
                        } 
                    }
                }
            }
        }

        public function payout(){
             $payout=0;
            for ($i = 1; $i < count($this->players); $i++){
                if ($this->bets[$i] > 0) {
                    if ($this->hand_status[$i] == "BlackJack!" ){
                        $payout += $this->bets[$i] * 2.5;
                        DB_connection::updateWins($_SESSION["id"]);
                       
                        
                        
                    }
                    else if ($this->hand_status[$i] == "Tie!" ){
                        $payout += $this->bets[$i];
                    } 
                    else if ($this->hand_status[$i] == "Win!" ){
                        $payout += $this->bets[$i] * 2;
                        DB_connection::updateWins($_SESSION["id"]);
                      
                    } 
                }
            }
            DB_connection::updateMoneyWon($_SESSION["id"], $payout);    
            DB_connection::addMoney($_SESSION["id"], $payout);
            echo "<h3 style='text-align: center;'>You won <b>$". number_format($payout, 2) . "</b>. Your balance is now <b>$" . number_format($_SESSION["userObj"]->getBalance(), 2) . "</b></h3>";
            
            
        }



}

