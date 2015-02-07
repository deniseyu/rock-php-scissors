<?php
require "./src/game.php";

class GameTest extends PHPUnit_Framework_TestCase
{
  // protected $game;
  // protected $rock;
  // protected function setUp()
  // {
  //   $game = new Game();
  //   $rock = new Rock();
  //   $paper = new Paper();
  //   $scissors = new Scissors();
  // }
  public function testCheckType()
  {
    $rock = new Rock();
    $paper = new Paper();
    $scissors = new Scissors();
    $this->assertEquals($rock->type, "Rock");
    $this->assertEquals($paper->type, "Paper");
    $this->assertEquals($scissors->type, "Scissors");
  }
  public function testDraw()
  {
    $game = new Game();
    $paper = new Paper();
    $this->assertEquals($game->evaluates($paper, $paper), "Draw");
  }
  public function testRockBeatsScissors()
  {
    $game = new Game();
    $rock = new Rock();
    $scissors = new Scissors();
    $this->assertEquals($game->evaluates($rock, $scissors), $rock);
  }
  public function testPaperBeatsRock()
  {
    $game = new Game();
    $rock = new Rock();
    $paper = new Paper();
    $this->assertEquals($game->evaluates($rock, $paper), $paper);
  }
  public function testScissorsBeatsPaper()
  {
    $game = new Game();
    $scissors = new Scissors();
    $paper = new Paper();
    $this->assertEquals($game->evaluates($scissors, $paper), $scissors);
  }
}

?>
