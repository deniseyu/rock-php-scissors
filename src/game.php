<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if (get_class($choiceOne) == get_class($choiceTwo)) {
      return "Draw";
    }
    else if (in_array(get_class($choiceTwo), $choiceOne->beats, true)) {
      return $choiceOne;
    }
    else {
      return $choiceTwo;
    }
  }
}
class Rock {
  public $beats = array("Scissors", "Lizard");
}
class Paper {
  public $beats = array("Rock", "Spock");
}
class Scissors {
  public $beats = array("Paper", "Lizard");
}
class Lizard {
  public $beats = array("Spock", "Paper");
}
class Spock {
  public $beats = array("Rock", "Scissors");
}
