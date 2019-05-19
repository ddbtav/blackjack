<?php
//JF: Decks class is filled up with cards. The constructor requires a number which will determine how many decks are used.
require_once "card.php";

class Decks
{

    public $_cards = array();

    private $_faces = array(
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        "J",
        "Q",
        "K",
        "A"
    );

    private $_suits = array(
        "d",
        "h",
        "c",
        "s"
    );

    public function __construct($num)
    {
        
        for($i = 0; $i < $num; $i++) {
            foreach ($this->_faces as $face) {
                foreach ($this->_suits as $suit) {

                    switch ($face) {
                        case "J":
                            $value = 10;
                            break;
                        case "Q":
                            $value = 10;
                            break;
                        case "K":
                            $value = 10;
                            break;
                        case "A":
                            $value = 11;
                            break;
                        default:
                            if (is_numeric($face)) {
                                $value = $face;
                            } else {
                                $value = 1;
                            }
                    }

                    $card = new Card();
                    $card->face = $face;
                    $card->suit = $suit;
                    $card->value = $value;

                    array_push($this->_cards, $card);

                }
            
            }
        }
    }
    
    //JF: Cheat function counts cards left in deck.
    public function cheat_cards_counting(){
        $cheat_line = "CHEAT: Cards left in the deck: ";
        $cards_left = sizeof($this->_cards);
        $cheat_line .= $cards_left . " | ";
        $no2_6s = 0;
        $no7_9s = 0;
        $no10_Ks = 0;
        $noAs = 0;

        foreach ($this->_cards as $card) {
            if ($card->face == 2 || $card->face == 3 || $card->face == 4 ||$card->face == 5 ||$card->face == 6){
                $no2_6s++;
            }
            else if ($card->face == 7 || $card->face == 8 || $card->face == 9){
                $no7_9s++;
            }
            else if ($card->face == 10 || $card->face == "J" || $card->face == "Q" || $card->face == "K"){
                $no10_Ks++;
            }            
            else if ($card->face == "A"){
                $noAs++;
            }
        }
        
        $cheat_line .= "2-6s: " . $no2_6s . " | 7-9s: " . $no7_9s . " | 10-Ks: " . $no10_Ks . " | As: " . $noAs . " |";
    
    //JF: Peek at next 3 cards
        $cheat_line .= $this->getNextCards();
        
        // return "CHEAT: Cards left in the deck: ... | 2-6s: ... | 7-9s: ... | 10-Ks: ... | As: ... |";
        return $cheat_line;
    }
    
    //JF: Function to get next 3 cards, so user can determine which hand would be best to hit.
    public function getNextCards() {
        $nextCards = "<br>" . $this->_cards[0]->face . ", " . $this->_cards[1]->face . ", and " . $this->_cards[2]->face . " are the next three cards.";
        
        return $nextCards;
    }

    public function showDeckCards()
    {
        foreach ($this->_cards as $card) {
            $card->createImagePath();
        }
    }

    public function showScoreCards()
    {
        foreach ($this->_cards as $card) {
            $card_value = $card->getCardValue();
        }
    }
}

?>