<?php
require "./src/game.php";

class GameTest extends PHPUnit_Framework_TestCase
{
  protected $game;
  protected $rock;
  protected $paper;
  protected $scissors;

  protected function setUp()
  {
    $this->game = new Game();
    $this->rock = new Rock();
    $this->paper = new Paper();
    $this->scissors = new Scissors();
  }
  public function testCheckType()
  {
    $this->assertEquals($this->rock->type, "Rock");
    $this->assertEquals($this->paper->type, "Paper");
    $this->assertEquals($this->scissors->type, "Scissors");
  }
  public function testDraw()
  {
    $this->assertEquals($this->game->evaluates($this->paper, $this->paper), "Draw");
  }
  public function testRockBeatsScissors()
  {
    $this->assertEquals($this->game->evaluates($this->rock, $this->scissors), $this->rock);
  }
  public function testPaperBeatsRock()
  {
    $this->assertEquals($this->game->evaluates($this->rock, $this->paper), $this->paper);
  }
  public function testScissorsBeatsPaper()
  {
    $this->assertEquals($this->game->evaluates($this->scissors, $this->paper), $this->scissors);
  }
}

?>
