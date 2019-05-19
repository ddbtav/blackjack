<?php
//JF: Card class. Each card has a suit, face, and value. We use a method to select the image.

class Card
{

    public $suit;

    public $face;

    public $value;

    public function createImagePath()
    {
        $linkToImage = 'cards/' . $this->face . $this->suit . '.jpg';
        return $linkToImage;
    }
}

?>