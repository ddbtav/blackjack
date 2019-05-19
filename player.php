<?php

class Player
{
    public $user;
    public $hand = array();

    public $allowMoreCards = true;

    public $name;

    public $cardVisible;

    public $scoreVisible;

    function __construct($playerName)
    {
        $this->user = new User($playerName);
        $this->name = $playerName;
    }

    public function addCardToHand($card)
    {
        array_push($this->hand, $card);
        return $this->hand;
    }

    public function showHand()
    {
        foreach ($this->hand as $card) {
            if ($this->cardVisible == true) {
                echo "<img src = " . $card->createImagePath() . ">";
            } else {
                echo "<img src = " . "cards/blanc.jpg" . ">";
            }
        }
    }

  //JF: Calculates value of hand. Counts Aces and decreases value by 10 per ace to avoid bust.
    public function calculateScoreInHand()
    {
        
        $totalScoreinHand = 0;
        $aceCounter = 0;
        
        foreach ($this->hand as $cardValue)  {
                $totalScoreinHand += $cardValue->value;
                if($cardValue->face == 'A') {
                    $aceCounter++;
                }
        }
        
        if($totalScoreinHand <= 21 || $aceCounter == 0) {
            return $totalScoreinHand;
        }
        
        else {
            for($i = $aceCounter; $i > 0; $i--) {
                $totalScoreinHand -= 10;
            }
            return $totalScoreinHand;
        }
        
//                
//                if($cardValue->face == 'A' && $totalScoreinHand < 11) {
//                    $totalScoreinHand += $cardValue->value;
//                    $aceCounter++;
//                }
//                
//                elseif($cardValue->face == 'A' && $totalScoreinHand >= 11) {
//                    $totalScoreinHand += 1;
//                }
//            
//                else {
//                    $totalScoreinHand += $cardValue->value;
//                        while($aceCounter >= 1) {
//                            $aceCounter--;
//                            if($totalScoreinHand > 21) {
//                                
//                                $totalScoreinHand = $totalScoreinHand - 10;
//                            }
//                            
//                        }
//                    
//                }
//            }
//        
//        
//        
//        return $totalScoreinHand;
//    }
    }
}

class Computer extends Player
{

    public function __construct($playerName)
    {
        // $this->cardVisible = false;
        // $this->scoreVisible = false;
        $this->cardVisible = true;
        $this->scoreVisible = true;
        parent::__construct($playerName);
    }
    
    
    public function showHand()
    {
        $counter=0;
        foreach ($this->hand as $card) {
            $counter++;
            if ($counter > 1) {
                echo "<img src = " . $card->createImagePath() . ">";
            } else {
                echo "<img src = " . "cards/blanc.jpg" . ">";
            }
        }
    }
    
        public function show_all_cards()
    {
        foreach ($this->hand as $card) {
                echo "<img src = " . $card->createImagePath() . ">";
        }
    }
    
}

class Human extends Player
{

    public function __construct($playerName)
    {
        $this->scoreVisible = true;
        $this->cardVisible = true;
        parent::__construct($playerName);
    }
}
