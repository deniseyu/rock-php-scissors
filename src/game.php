<?php
class Game {
  public function evaluates($choiceOne, $choiceTwo) {
    if ($choiceOne->type == $choiceTwo->type) {
      return "Draw";
    }
    else if ($choiceOne->beats == $choiceTwo->type) {
      return $choiceOne;
    }
    else {
      return $choiceTwo;
    }
  }
}
class Rock {
  public $type = "Rock";
  public $beats = "Scissors";
}
class Paper {
  public $type = "Paper";
  public $beats = "Rock";
}
class Scissors {
  public $type = "Scissors";
  public $beats = "Paper";
}
?>