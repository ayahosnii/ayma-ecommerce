<?php

namespace App\FlashCardDecorators;

class FlashCardCollection implements IDisplay {
    protected $cards = [];

    public function addCard(IDisplay $card) {
        $this->cards[] = $card;
    }

    public function display() {
        $data = [];
        foreach ($this->cards as $card) {
            $data[] = $card->display();
        }
        return $data;
    }
}

