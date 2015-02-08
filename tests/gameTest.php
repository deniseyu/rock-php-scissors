<?php
require "./src/game.php";

class GameTest extends PHPUnit_Framework_TestCase
{
  protected $game;
  protected $rock;
  protected $paper;
  protected $scissors;
  protected $lizard;
  protected $spock;

  protected function setUp()
  {
    $this->game = new Game();
    $this->rock = new Rock();
    $this->paper = new Paper();
    $this->scissors = new Scissors();
    $this->lizard = new Lizard();
    $this->spock = new Spock();
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
  public function testSpockBeatsRock()
  {
    $this->assertEquals($this->game->evaluates($this->spock, $this->rock), $this->spock);
  }
  public function testPaperBeatsSpock()
  {
    $this->assertEquals($this->game->evaluates($this->paper, $this->spock), $this->paper);
  }
  public function testScissorsBeatsLizard()
  {
    $this->assertEquals($this->game->evaluates($this->scissors, $this->lizard), $this->scissors);
  }
  public function testRockBeatsLizard()
  {
    $this->assertEquals($this->game->evaluates($this->rock, $this->lizard), $this->rock);
  }
  public function testLizardBeatsSpock()
  {
    $this->assertEquals($this->game->evaluates($this->lizard, $this->spock), $this->lizard);
  }
  public function testSpockBeatsScissors()
  {
    $this->assertEquals($this->game->evaluates($this->spock, $this->scissors), $this->spock);
  }
  public function testLizardBeatsPaper()
  {
    $this->assertEquals($this->game->evaluates($this->paper, $this->lizard), $this->lizard);
  }
}
