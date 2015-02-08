<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if ($choiceOne->type == $choiceTwo->type) {
      return "Draw";
    }
    else if (in_array($choiceTwo->type, $choiceOne->beats, true)) {
      return $choiceOne;
    }
    else {
      return $choiceTwo;
    }
  }
}
class Rock {
  public $type = "Rock";
  public $beats = array("Scissors", "Lizard");
}
class Paper {
  public $type = "Paper";
  public $beats = array("Rock", "Spock");
}
class Scissors {
  public $type = "Scissors";
  public $beats = array("Paper", "Lizard");
}
class Lizard {
  public $type = "Lizard";
  public $beats = array("Spock", "Paper");
}
class Spock {
  public $type = "Spock";
  public $beats = array("Rock", "Scissors");
}
?>